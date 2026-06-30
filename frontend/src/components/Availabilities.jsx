import { useEffect, useState } from 'react';
import { Link, useLocation, useNavigate } from 'react-router-dom';
import api from '../api/axios';

const DAY_LABELS = {
  0: 'Domingo',
  1: 'Segunda-feira',
  2: 'Terça-feira',
  3: 'Quarta-feira',
  4: 'Quinta-feira',
  5: 'Sexta-feira',
  6: 'Sábado',
};

function Availabilities() {
  const location = useLocation();
  const navigate = useNavigate();
  const [availabilities, setAvailabilities] = useState([]);
  const [users, setUsers] = useState([]);
  const [successMessage, setSuccessMessage] = useState('');
  const [errorMessage, setErrorMessage] = useState('');
  const [isLoading, setIsLoading] = useState(true);
  const [itemToDelete, setItemToDelete] = useState(null);
  const [isDeleting, setIsDeleting] = useState(false);

  const closeDeleteModal = () => {
    if (!isDeleting) {
      setItemToDelete(null);
    }
  };

  const handleDeleteConfirm = async () => {
    if (!itemToDelete) {
      return;
    }

    setErrorMessage('');
    setSuccessMessage('');
    setIsDeleting(true);

    try {
      await api.delete(`/availabilities/${itemToDelete.id}`);

      setAvailabilities((current) => current.filter((a) => a.id !== itemToDelete.id));
      setSuccessMessage('Disponibilidade excluída com sucesso.');
      setItemToDelete(null);
    } catch (error) {
      setErrorMessage(error.response?.data?.message || 'Não foi possível excluir a disponibilidade.');
      setItemToDelete(null);
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
    const loadData = async () => {
      try {
        const meResponse = await api.get('/me');

        if (meResponse.data?.user?.role !== 'ADMIN') {
          navigate('/users', { replace: true });
          return;
        }

        const [availabilitiesResponse, usersResponse] = await Promise.all([
          api.get('/availabilities'),
          api.get('/users'),
        ]);

        setAvailabilities(availabilitiesResponse.data || []);
        setUsers(usersResponse.data || []);
      } catch (error) {
        if (error.response?.status === 401) {
          navigate('/login', { replace: true });
          return;
        }

        setErrorMessage(error.response?.data?.message || 'Não foi possível carregar as disponibilidades.');
      } finally {
        setIsLoading(false);
      }
    };

    loadData();
  }, [navigate]);

  const getUserName = (userId) => {
    const user = users.find((u) => u.id === userId);
    return user ? user.name : `Usuário #${userId}`;
  };

  return (
    <div className="container mt-4">
      <div className="d-flex justify-content-between align-items-center mb-3">
        <h2 className="h4 mb-0">Disponibilidades</h2>
        <div className="d-flex gap-2">
          <Link to="/users" className="btn btn-outline-secondary btn-sm">
            Usuários
          </Link>
          <Link to="/availabilities/new" className="btn btn-primary">
            Nova Disponibilidade
          </Link>
        </div>
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
          Carregando disponibilidades...
        </div>
      ) : (
        <div className="table-responsive">
          <table className="table table-striped table-hover">
            <thead>
              <tr>
                <th>Atendente</th>
                <th>Dia da Semana</th>
                <th>Hora Inicial</th>
                <th>Hora Final</th>
                <th>Status</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              {availabilities.length === 0 ? (
                <tr>
                  <td colSpan={6} className="text-center">
                    Nenhuma disponibilidade cadastrada.
                  </td>
                </tr>
              ) : (
                availabilities.map((availability) => (
                  <tr key={availability.id}>
                    <td>{getUserName(availability.user_id)}</td>
                    <td>{DAY_LABELS[availability.day_of_week] ?? availability.day_of_week}</td>
                    <td>{availability.start_time?.substring(0, 5)}</td>
                    <td>{availability.end_time?.substring(0, 5)}</td>
                    <td>
                      <span className={`badge ${availability.active ? 'bg-success' : 'bg-secondary'}`}>
                        {availability.active ? 'Ativo' : 'Inativo'}
                      </span>
                    </td>
                    <td>
                      <div className="d-flex gap-2">
                        <Link
                          to={`/availabilities/${availability.id}/edit`}
                          className="btn btn-sm btn-outline-primary"
                        >
                          Editar
                        </Link>
                        <button
                          type="button"
                          className="btn btn-sm btn-outline-danger"
                          onClick={() => setItemToDelete(availability)}
                        >
                          Excluir
                        </button>
                      </div>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      )}

      {itemToDelete ? (
        <>
          <div
            className="modal fade show d-block"
            tabIndex="-1"
            role="dialog"
            aria-labelledby="deleteAvailabilityModalLabel"
            aria-modal="true"
          >
            <div className="modal-dialog">
              <div className="modal-content">
                <div className="modal-header">
                  <h5 className="modal-title" id="deleteAvailabilityModalLabel">
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
                  <p className="mb-2">Deseja realmente excluir esta disponibilidade?</p>
                  <p className="mb-1">
                    <strong>Atendente:</strong> {getUserName(itemToDelete.user_id)}
                  </p>
                  <p className="mb-1">
                    <strong>Dia:</strong> {DAY_LABELS[itemToDelete.day_of_week] ?? itemToDelete.day_of_week}
                  </p>
                  <p className="mb-0">
                    <strong>Horário:</strong> {itemToDelete.start_time?.substring(0, 5)} – {itemToDelete.end_time?.substring(0, 5)}
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

export default Availabilities;
