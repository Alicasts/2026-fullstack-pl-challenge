import { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import api from '../api/axios';

const DAY_OPTIONS = [
  { value: '0', label: 'Domingo' },
  { value: '1', label: 'Segunda-feira' },
  { value: '2', label: 'Terça-feira' },
  { value: '3', label: 'Quarta-feira' },
  { value: '4', label: 'Quinta-feira' },
  { value: '5', label: 'Sexta-feira' },
  { value: '6', label: 'Sábado' },
];

function AvailabilityCreate() {
  const navigate = useNavigate();
  const [isCheckingAccess, setIsCheckingAccess] = useState(true);
  const [attendants, setAttendants] = useState([]);
  const [userId, setUserId] = useState('');
  const [dayOfWeek, setDayOfWeek] = useState('1');
  const [startTime, setStartTime] = useState('');
  const [endTime, setEndTime] = useState('');
  const [active, setActive] = useState('1');
  const [errorMessage, setErrorMessage] = useState('');
  const [fieldErrors, setFieldErrors] = useState({});
  const [isSubmitting, setIsSubmitting] = useState(false);

  useEffect(() => {
    const checkAccess = async () => {
      try {
        const meResponse = await api.get('/me');

        if (meResponse.data?.user?.role !== 'ADMIN') {
          navigate('/users', { replace: true });
          return;
        }

        const usersResponse = await api.get('/users');
        const attendantList = (usersResponse.data || []).filter((u) => u.role === 'ATTENDANT');
        setAttendants(attendantList);

        if (attendantList.length > 0) {
          setUserId(String(attendantList[0].id));
        }
      } catch {
        navigate('/login', { replace: true });
        return;
      } finally {
        setIsCheckingAccess(false);
      }
    };

    checkAccess();
  }, [navigate]);

  const getFieldError = (field) => fieldErrors[field]?.[0] || '';

  const handleSubmit = async (event) => {
    event.preventDefault();
    setErrorMessage('');
    setFieldErrors({});
    setIsSubmitting(true);

    try {
      await api.post('/availabilities', {
        user_id: parseInt(userId, 10),
        day_of_week: parseInt(dayOfWeek, 10),
        start_time: `${startTime}:00`,
        end_time: `${endTime}:00`,
        active: active === '1',
      });

      navigate('/availabilities', { state: { successMessage: 'Disponibilidade cadastrada com sucesso.' } });
    } catch (error) {
      if (error.response?.status === 422) {
        setFieldErrors(error.response.data?.errors || {});
        setErrorMessage(error.response.data?.message || 'Verifique os campos informados.');
      } else if (error.response?.status === 403) {
        setErrorMessage('Você não tem permissão para cadastrar disponibilidades.');
      } else {
        setErrorMessage(error.response?.data?.message || 'Não foi possível cadastrar a disponibilidade.');
      }
    } finally {
      setIsSubmitting(false);
    }
  };

  if (isCheckingAccess) {
    return (
      <div className="container mt-4">
        <div className="alert alert-info" role="status">
          Verificando permissões...
        </div>
      </div>
    );
  }

  return (
    <div className="container mt-4">
      <div className="row justify-content-center">
        <div className="col-md-6">
          <div className="d-flex justify-content-between align-items-center mb-3">
            <h2 className="h4 mb-0">Nova Disponibilidade</h2>
            <Link to="/availabilities" className="btn btn-outline-secondary btn-sm">
              Voltar
            </Link>
          </div>

          <div className="card shadow-sm">
            <div className="card-body">
              <form onSubmit={handleSubmit}>
                <div className="mb-3">
                  <label htmlFor="userId" className="form-label">
                    Atendente*
                  </label>
                  {attendants.length === 0 ? (
                    <div className="alert alert-warning py-2 mb-0">
                      Nenhum atendente cadastrado. Cadastre um usuário do tipo Atendente primeiro.
                    </div>
                  ) : (
                    <select
                      id="userId"
                      className={`form-select${getFieldError('user_id') ? ' is-invalid' : ''}`}
                      value={userId}
                      onChange={(event) => setUserId(event.target.value)}
                      required
                    >
                      {attendants.map((attendant) => (
                        <option key={attendant.id} value={String(attendant.id)}>
                          {attendant.name}
                        </option>
                      ))}
                    </select>
                  )}
                  {getFieldError('user_id') ? (
                    <div className="invalid-feedback">{getFieldError('user_id')}</div>
                  ) : null}
                </div>

                <div className="mb-3">
                  <label htmlFor="dayOfWeek" className="form-label">
                    Dia da Semana*
                  </label>
                  <select
                    id="dayOfWeek"
                    className={`form-select${getFieldError('day_of_week') ? ' is-invalid' : ''}`}
                    value={dayOfWeek}
                    onChange={(event) => setDayOfWeek(event.target.value)}
                    required
                  >
                    {DAY_OPTIONS.map((day) => (
                      <option key={day.value} value={day.value}>
                        {day.label}
                      </option>
                    ))}
                  </select>
                  {getFieldError('day_of_week') ? (
                    <div className="invalid-feedback">{getFieldError('day_of_week')}</div>
                  ) : null}
                </div>

                <div className="mb-3">
                  <label htmlFor="startTime" className="form-label">
                    Hora Inicial*
                  </label>
                  <input
                    id="startTime"
                    type="time"
                    className={`form-control${getFieldError('start_time') ? ' is-invalid' : ''}`}
                    value={startTime}
                    onChange={(event) => setStartTime(event.target.value)}
                    required
                  />
                  {getFieldError('start_time') ? (
                    <div className="invalid-feedback">{getFieldError('start_time')}</div>
                  ) : null}
                </div>

                <div className="mb-3">
                  <label htmlFor="endTime" className="form-label">
                    Hora Final*
                  </label>
                  <input
                    id="endTime"
                    type="time"
                    className={`form-control${getFieldError('end_time') ? ' is-invalid' : ''}`}
                    value={endTime}
                    onChange={(event) => setEndTime(event.target.value)}
                    required
                  />
                  {getFieldError('end_time') ? (
                    <div className="invalid-feedback">{getFieldError('end_time')}</div>
                  ) : null}
                </div>

                <div className="mb-3">
                  <label htmlFor="active" className="form-label">
                    Status*
                  </label>
                  <select
                    id="active"
                    className={`form-select${getFieldError('active') ? ' is-invalid' : ''}`}
                    value={active}
                    onChange={(event) => setActive(event.target.value)}
                    required
                  >
                    <option value="1">Ativo</option>
                    <option value="0">Inativo</option>
                  </select>
                  {getFieldError('active') ? (
                    <div className="invalid-feedback">{getFieldError('active')}</div>
                  ) : null}
                </div>

                {errorMessage ? (
                  <div className="alert alert-danger" role="alert">
                    {errorMessage}
                  </div>
                ) : null}

                <button
                  type="submit"
                  className="btn btn-primary w-100"
                  disabled={isSubmitting || attendants.length === 0}
                >
                  {isSubmitting ? 'Salvando...' : 'Salvar'}
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default AvailabilityCreate;
