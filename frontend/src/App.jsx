import { BrowserRouter, Navigate, Route, Routes } from 'react-router-dom';
import Availabilities from './components/Availabilities';
import AvailabilityCreate from './components/AvailabilityCreate';
import AvailabilityEdit from './components/AvailabilityEdit';
import Login from './components/Login';
import UserCreate from './components/UserCreate';
import UserEdit from './components/UserEdit';
import Users from './components/Users';

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/login" element={<Login />} />
        <Route path="/users" element={<Users />} />
        <Route path="/users/new" element={<UserCreate />} />
        <Route path="/users/:id/edit" element={<UserEdit />} />
        <Route path="/availabilities" element={<Availabilities />} />
        <Route path="/availabilities/new" element={<AvailabilityCreate />} />
        <Route path="/availabilities/:id/edit" element={<AvailabilityEdit />} />
        <Route path="*" element={<Navigate to="/login" replace />} />
      </Routes>
    </BrowserRouter>
  );
}

export default App;