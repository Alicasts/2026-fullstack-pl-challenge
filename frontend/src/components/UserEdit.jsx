import { useEffect, useState } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';
import api from '../api/axios';

function UserEdit() {
  const navigate = useNavigate();
  const { id } = useParams();
  const [isLoading, setIsLoading] = useState(true);
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [role, setRole] = useState('ATTENDANT');
  const [errorMessage, setErrorMessage] = useState('');
  const [fieldErrors, setFieldErrors] = useState({});
  const [isSubmitting, setIsSubmitting] = useState(false);

  useEffect(() => {
    const loadPage = async () => {
      try {
        const meResponse = await api.get('/me');

        if (meResponse.data?.user?.role !== 'ADMIN') {
          navigate('/users', { replace: true });
          return;
        }

        const usersResponse = await api.get('/users');
        const user = (usersResponse.data || []).find((item) => String(item.id) === String(id));

        if (!user) {
          setErrorMessage('Usuário não encontrado.');
          return;
        }

        setName(user.name);
        setEmail(user.email);
        setRole(user.role);
      } catch (error) {
        if (error.response?.status === 401) {
          navigate('/login', { replace: true });
          return;
        }

        setErrorMessage(error.response?.data?.message || 'Não foi possível carregar o usuário.');
      } finally {
        setIsLoading(false);
      }
    };

    loadPage();
  }, [id, navigate]);

  const getFieldError = (field) => fieldErrors[field]?.[0] || '';

  const handleSubmit = async (event) => {
    event.preventDefault();
    setErrorMessage('');
    setFieldErrors({});
    setIsSubmitting(true);

    try {
      await api.put(`/users/${id}`, {
        name,
        role,
      });

      navigate('/users', { state: { successMessage: 'Usuário atualizado com sucesso.' } });
    } catch (error) {
      if (error.response?.status === 422) {
        setFieldErrors(error.response.data?.errors || {});
        setErrorMessage(error.response.data?.message || 'Verifique os campos informados.');
      } else if (error.response?.status === 403) {
        setErrorMessage('Você não tem permissão para editar este usuário.');
      } else {
        setErrorMessage(error.response?.data?.message || 'Não foi possível atualizar o usuário.');
      }
    } finally {
      setIsSubmitting(false);
    }
  };

  if (isLoading) {
    return (
      <div className="container mt-4">
        <div className="alert alert-info" role="status">
          Carregando usuário...
        </div>
      </div>
    );
  }

  if (errorMessage && !name) {
    return (
      <div className="container mt-4">
        <div className="d-flex justify-content-between align-items-center mb-3">
          <h2 className="h4 mb-0">Editar Usuário</h2>
          <Link to="/users" className="btn btn-outline-secondary btn-sm">
            Voltar
          </Link>
        </div>
        <div className="alert alert-danger" role="alert">
          {errorMessage}
        </div>
      </div>
    );
  }

  return (
    <div className="container mt-4">
      <div className="row justify-content-center">
        <div className="col-md-6">
          <div className="d-flex justify-content-between align-items-center mb-3">
            <h2 className="h4 mb-0">Editar Usuário</h2>
            <Link to="/users" className="btn btn-outline-secondary btn-sm">
              Voltar
            </Link>
          </div>

          <div className="card shadow-sm">
            <div className="card-body">
              <form onSubmit={handleSubmit}>
                <div className="mb-3">
                  <label htmlFor="name" className="form-label">
                    Nome*
                  </label>
                  <input
                    id="name"
                    type="text"
                    className={`form-control${getFieldError('name') ? ' is-invalid' : ''}`}
                    value={name}
                    onChange={(event) => setName(event.target.value)}
                    required
                  />
                  {getFieldError('name') ? (
                    <div className="invalid-feedback">{getFieldError('name')}</div>
                  ) : null}
                </div>

                <div className="mb-3">
                  <label htmlFor="role" className="form-label">
                    Tipo de Usuário*
                  </label>
                  <select
                    id="role"
                    className={`form-select${getFieldError('role') ? ' is-invalid' : ''}`}
                    value={role}
                    onChange={(event) => setRole(event.target.value)}
                    required
                  >
                    <option value="ADMIN">Administrador</option>
                    <option value="ATTENDANT">Atendente</option>
                  </select>
                  {getFieldError('role') ? (
                    <div className="invalid-feedback">{getFieldError('role')}</div>
                  ) : null}
                </div>

                <div className="mb-3">
                  <label htmlFor="email" className="form-label">
                    E-mail
                  </label>
                  <input
                    id="email"
                    type="email"
                    className="form-control"
                    value={email}
                    readOnly
                    disabled
                  />
                </div>

                {errorMessage ? (
                  <div className="alert alert-danger" role="alert">
                    {errorMessage}
                  </div>
                ) : null}

                <button type="submit" className="btn btn-primary w-100" disabled={isSubmitting}>
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

export default UserEdit;
