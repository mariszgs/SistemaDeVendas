import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import axios from "axios";

function PedidosListar() {
  const [pedidos, setPedidos] = useState([]);

  useEffect(() => {
    axios
      .get("http://localhost/sdv/backend/public/orders")
      .then((res) => setPedidos(res.data.pedidos))
      .catch((err) => console.error(err));
  }, []);

  function handleDelete(id) {
    if (!window.confirm("Tem certeza que deseja deletar?")) return;

    axios
      .delete(`http://localhost/sdv/backend/public/orders/delete/${id}`)
      .then(() => setPedidos(pedidos.filter((p) => p.id !== id)))
      .catch((err) => console.error(err));
  }

  return (
    <div>
      <h2 className="text-2xl font-bold mb-4">Pedidos</h2>
      <Link
        to="/pedidos/criar"
        className="px-4 py-2 bg-green-600 text-white rounded"
      >
        Novo Pedido
      </Link>
      <table className="w-full mt-4 border">
        <thead>
          <tr className="bg-gray-200">
            <th className="p-2">ID</th>
            <th className="p-2">Cliente</th>
            <th className="p-2">Total</th>
            <th className="p-2">Ações</th>
          </tr>
        </thead>
        <tbody>
          {pedidos.map((p) => (
            <tr key={p.id} className="border-t">
              <td className="p-2">{p.id}</td>
              <td className="p-2">{p.cliente_nome}</td>
              <td className="p-2">R$ {p.total}</td>
              <td className="p-2 flex gap-2">
                <Link
                  to={`/pedidos/editar/${p.id}`}
                  className="px-2 py-1 bg-blue-500 text-white rounded"
                >
                  Editar
                </Link>
                <button
                  onClick={() => handleDelete(p.id)}
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

export default PedidosListar;
