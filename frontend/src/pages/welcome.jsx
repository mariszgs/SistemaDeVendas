import { useNavigate } from "react-router-dom";
import "./welcome.css";

function Welcome() {
  const navigate = useNavigate();

  return (
    <div className="welcome-container">
      <div className="welcome-card">
        <h1 className="welcome-title">Sistema de Vendas</h1>
        <p className="welcome-subtitle">Gerencie clientes, produtos e pedidos de forma prática e rápida.</p>
        <div className="welcome-buttons">
          <button onClick={() => navigate("/login")} className="btn btn-login">
            Entrar
          </button>
          <button onClick={() => navigate("/register")} className="btn btn-register">
            Registrar
          </button>
        </div>
      </div>
    </div>
  );
}

export default Welcome;
