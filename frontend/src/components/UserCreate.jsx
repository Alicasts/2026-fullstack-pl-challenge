import { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import api from '../api/axios';

function UserCreate() {
  const navigate = useNavigate();
  const [isCheckingAccess, setIsCheckingAccess] = useState(true);
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [role, setRole] = useState('ATTENDANT');
  const [password, setPassword] = useState('');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');
  const [errorMessage, setErrorMessage] = useState('');
  const [fieldErrors, setFieldErrors] = useState({});
  const [isSubmitting, setIsSubmitting] = useState(false);

  useEffect(() => {
    const checkAccess = async () => {
      try {
        const response = await api.get('/me');

        if (response.data?.user?.role !== 'ADMIN') {
          navigate('/users', { replace: true });
          return;
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
      await api.post('/users', {
        name,
        email,
        role,
        password,
        password_confirmation: passwordConfirmation,
      });

      navigate('/users');
    } catch (error) {
      if (error.response?.status === 422) {
        setFieldErrors(error.response.data?.errors || {});
        setErrorMessage(error.response.data?.message || 'Verifique os campos informados.');
      } else if (error.response?.status === 403) {
        setErrorMessage('Você não tem permissão para cadastrar usuários.');
      } else {
        setErrorMessage(error.response.data?.message || 'Não foi possível cadastrar o usuário.');
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
            <h2 className="h4 mb-0">Novo Usuário</h2>
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
                    E-mail*
                  </label>
                  <input
                    id="email"
                    type="email"
                    className={`form-control${getFieldError('email') ? ' is-invalid' : ''}`}
                    value={email}
                    onChange={(event) => setEmail(event.target.value)}
                    required
                  />
                  {getFieldError('email') ? (
                    <div className="invalid-feedback">{getFieldError('email')}</div>
                  ) : null}
                </div>

                <div className="mb-3">
                  <label htmlFor="password" className="form-label">
                    Senha*
                  </label>
                  <input
                    id="password"
                    type="password"
                    className={`form-control${getFieldError('password') ? ' is-invalid' : ''}`}
                    value={password}
                    onChange={(event) => setPassword(event.target.value)}
                    minLength={8}
                    required
                  />
                  {getFieldError('password') ? (
                    <div className="invalid-feedback">{getFieldError('password')}</div>
                  ) : null}
                </div>

                <div className="mb-3">
                  <label htmlFor="passwordConfirmation" className="form-label">
                    Confirme a Senha*
                  </label>
                  <input
                    id="passwordConfirmation"
                    type="password"
                    className={`form-control${getFieldError('password_confirmation') ? ' is-invalid' : ''}`}
                    value={passwordConfirmation}
                    onChange={(event) => setPasswordConfirmation(event.target.value)}
                    minLength={8}
                    required
                  />
                  {getFieldError('password_confirmation') ? (
                    <div className="invalid-feedback">{getFieldError('password_confirmation')}</div>
                  ) : null}
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

export default UserCreate;
