import { BrowserRouter, Navigate, Route, Routes } from 'react-router-dom';
import Login from './components/Login';
import UserCreate from './components/UserCreate';
import Users from './components/Users';

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/login" element={<Login />} />
        <Route path="/users" element={<Users />} />
        <Route path="/users/new" element={<UserCreate />} />
        <Route path="*" element={<Navigate to="/login" replace />} />
      </Routes>
    </BrowserRouter>
  );
}

export default App;