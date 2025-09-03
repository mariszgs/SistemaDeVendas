import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import axios from "axios";

function ClientesListar() {
  const [clientes, setClientes] = useState([]);

  useEffect(() => {
    axios
      .get("http://localhost/sdv/backend/public/clients")
      .then((res) => setClientes(res.data.clientes))
      .catch((err) => console.error(err));
  }, []);

  function handleDelete(id) {
    if (!window.confirm("Tem certeza que deseja deletar?")) return;

    axios
      .delete(`http://localhost/sdv/backend/public/clients/delete/${id}`)
      .then(() => setClientes(clientes.filter((c) => c.id !== id)))
      .catch((err) => console.error(err));
  }

  return (
    <div>
      <h2 className="text-2xl font-bold mb-4">Clientes</h2>
      <Link
        to="/clientes/criar"
        className="px-4 py-2 bg-green-600 text-white rounded"
      >
        Novo Cliente
      </Link>
      <table className="w-full mt-4 border">
        <thead>
          <tr className="bg-gray-200">
            <th className="p-2">Nome</th>
            <th className="p-2">Email</th>
            <th className="p-2">Ações</th>
          </tr>
        </thead>
        <tbody>
          {clientes.map((c) => (
            <tr key={c.id} className="border-t">
              <td className="p-2">{c.nome}</td>
              <td className="p-2">{c.email}</td>
              <td className="p-2 flex gap-2">
                <Link
                  to={`/clientes/editar/${c.id}`}
                  className="px-2 py-1 bg-blue-500 text-white rounded"
                >
                  Editar
                </Link>
                <button
                  onClick={() => handleDelete(c.id)}
                  className="px-2 py-1 bg-red-500 text-white rounded"
                >
                  Deletar
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}

export default ClientesListar;
