import { useEffect, useState } from 'react';
import { Link, useLocation, useNavigate } from 'react-router-dom';
import api from '../api/axios';

function Users() {
  const location = useLocation();
  const navigate = useNavigate();
  const [users, setUsers] = useState([]);
  const [currentUserRole, setCurrentUserRole] = useState(null);
  const [successMessage, setSuccessMessage] = useState('');
  const [errorMessage, setErrorMessage] = useState('');
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    if (location.state?.successMessage) {
      setSuccessMessage(location.state.successMessage);
      navigate('.', { replace: true, state: {} });
    }
  }, [location.state, navigate]);

  useEffect(() => {
    const loadUsers = async () => {
      try {
        const [usersResponse, meResponse] = await Promise.all([
          api.get('/users'),
          api.get('/me'),
        ]);

        setUsers(usersResponse.data || []);
        setCurrentUserRole(meResponse.data?.user?.role || null);
      } catch (error) {
        setErrorMessage(error.response?.data?.message || 'Não foi possível carregar os usuários.');
      } finally {
        setIsLoading(false);
      }
    };

    loadUsers();
  }, []);

  return (
    <div className="container mt-4">
      <div className="d-flex justify-content-between align-items-center mb-3">
        <h2 className="h4 mb-0">Usuários</h2>
        {currentUserRole === 'ADMIN' ? (
          <Link to="/users/new" className="btn btn-primary">
            Novo Usuário
          </Link>
        ) : null}
      </div>

      {successMessage ? (
        <div className="alert alert-success" role="alert">
          {successMessage}
        </div>
      ) : null}

      {errorMessage ? (
        <div className="alert alert-danger" role="alert">
          {errorMessage}
        </div>
      ) : null}

      {isLoading ? (
        <div className="alert alert-info" role="status">
          Carregando usuários...
        </div>
      ) : (
        <div className="table-responsive">
          <table className="table table-striped table-hover">
            <thead>
              <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Perfil</th>
                {currentUserRole === 'ADMIN' ? <th>Ações</th> : null}
              </tr>
            </thead>
            <tbody>
              {users.length === 0 ? (
                <tr>
                  <td colSpan={currentUserRole === 'ADMIN' ? 4 : 3} className="text-center">
                    Nenhum usuário encontrado.
                  </td>
                </tr>
              ) : (
                users.map((user) => (
                  <tr key={user.id}>
                    <td>{user.name}</td>
                    <td>{user.email}</td>
                    <td>{user.role}</td>
                    {currentUserRole === 'ADMIN' ? (
                      <td>
                        <Link to={`/users/${user.id}/edit`} className="btn btn-sm btn-outline-primary">
                          Editar
                        </Link>
                      </td>
                    ) : null}
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      )}
    </div>
  );
}

export default Users;
