import { Link } from "react-router-dom";

function ClientesPainel() {
  return (
    <div>
      <h1 className="text-2xl font-bold mb-4">Gerenciar Clientes</h1>
      <div className="flex gap-4">
        <Link
          to="/clientes/listar"
          className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
        >
          Listar
        </Link>
        <Link
          to="/clientes/criar"
          className="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
        >
          Criar
        </Link>
        <Link
          to="/clientes/editar/1"
          className="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600"
        >
          Editar (exemplo)
        </Link>
        <Link
          to="/clientes/deletar/1"
          className="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
        >
          Deletar (exemplo)
        </Link>
      </div>
    </div>
  );
}

export default ClientesPainel;
