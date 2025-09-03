// src/pages/clientes/painel.jsx
import { useNavigate } from "react-router-dom";

function ClientesPainel() {
  const navigate = useNavigate();

  return (
    <div>
      <h1 className="text-2xl font-bold mb-4">Painel de Clientes</h1>
      <div className="flex flex-col gap-2">
        <button
          onClick={() => navigate("/clientes")}
          className="px-4 py-2 bg-blue-600 text-white rounded"
        >
          Listar
        </button>
        <button
          onClick={() => navigate("/clientes/criar")}
          className="px-4 py-2 bg-green-600 text-white rounded"
        >
          Criar
        </button>
      </div>
    </div>
  );
}

export default ClientesPainel;
