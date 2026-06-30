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
  const [currentUserId, setCurrentUserId] = useState(null);
  const [isLoading, setIsLoading] = useState(true);
  const [userToDelete, setUserToDelete] = useState(null);
  const [isDeleting, setIsDeleting] = useState(false);

  const closeDeleteModal = () => {
    if (!isDeleting) {
      setUserToDelete(null);
    }
  };

  const handleDeleteConfirm = async () => {
    if (!userToDelete) {
      return;
    }

    setErrorMessage('');
    setSuccessMessage('');
    setIsDeleting(true);

    try {
      await api.delete(`/users/${userToDelete.id}`);

      setUsers((currentUsers) => currentUsers.filter((user) => user.id !== userToDelete.id));
      setSuccessMessage('Usuário excluído com sucesso.');
      setUserToDelete(null);
    } catch (error) {
      if (error.response?.status === 422 || error.response?.status === 403 || error.response?.status === 404) {
        setErrorMessage(error.response?.data?.message || 'Não foi possível excluir o usuário.');
      } else {
        setErrorMessage(error.response?.data?.message || 'Ocorreu um erro inesperado ao excluir o usuário.');
      }

      setUserToDelete(null);
    } finally {
      setIsDeleting(false);
    }
  };

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
        setCurrentUserId(meResponse.data?.user?.id || null);
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
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              {users.length === 0 ? (
                <tr>
                  <td colSpan={4} className="text-center">
                    Nenhum usuário encontrado.
                  </td>
                </tr>
              ) : (
                users.map((user) => {
                  const canEdit =
                    currentUserRole === 'ADMIN' || String(user.id) === String(currentUserId);
                  const canDelete = currentUserRole === 'ADMIN';

                  return (
                    <tr key={user.id}>
                      <td>{user.name}</td>
                      <td>{user.email}</td>
                      <td>{user.role}</td>
                      <td>
                        <div className="d-flex gap-2">
                          {canEdit ? (
                            <Link to={`/users/${user.id}/edit`} className="btn btn-sm btn-outline-primary">
                              Editar
                            </Link>
                          ) : null}
                          {canDelete ? (
                            <button
                              type="button"
                              className="btn btn-sm btn-outline-danger"
                              onClick={() => setUserToDelete(user)}
                            >
                              Excluir
                            </button>
                          ) : null}
                        </div>
                      </td>
                    </tr>
                  );
                })
              )}
            </tbody>
          </table>
        </div>
      )}

      {userToDelete ? (
        <>
          <div
            className="modal fade show d-block"
            tabIndex="-1"
            role="dialog"
            aria-labelledby="deleteUserModalLabel"
            aria-modal="true"
          >
            <div className="modal-dialog">
              <div className="modal-content">
                <div className="modal-header">
                  <h5 className="modal-title" id="deleteUserModalLabel">
                    Confirmar exclusão
                  </h5>
                  <button
                    type="button"
                    className="btn-close"
                    aria-label="Fechar"
                    onClick={closeDeleteModal}
                    disabled={isDeleting}
                  />
                </div>
                <div className="modal-body">
                  <p className="mb-2">Deseja realmente excluir este usuário?</p>
                  <p className="mb-1">
                    <strong>Nome:</strong> {userToDelete.name}
                  </p>
                  <p className="mb-0">
                    <strong>E-mail:</strong> {userToDelete.email}
                  </p>
                </div>
                <div className="modal-footer">
                  <button
                    type="button"
                    className="btn btn-secondary"
                    onClick={closeDeleteModal}
                    disabled={isDeleting}
                  >
                    Cancelar
                  </button>
                  <button
                    type="button"
                    className="btn btn-danger"
                    onClick={handleDeleteConfirm}
                    disabled={isDeleting}
                  >
                    {isDeleting ? 'Excluindo...' : 'Confirmar exclusão'}
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div className="modal-backdrop fade show" />
        </>
      ) : null}
    </div>
  );
}

export default Users;
