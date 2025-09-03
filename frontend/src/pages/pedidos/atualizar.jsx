import { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import axios from "axios";

function PedidosAtualizar() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [pedido, setPedido] = useState({
    cliente_id: "",
    itens: "",
    total: "",
  });

  useEffect(() => {
    axios
      .get(`http://localhost/sdv/backend/public/orders/${id}`)
      .then((res) => setPedido(res.data.pedido))
      .catch((err) => console.error(err));
  }, [id]);

  function handleChange(e) {
    setPedido({ ...pedido, [e.target.name]: e.target.value });
  }

  function handleSubmit(e) {
    e.preventDefault();
    axios
      .post(
        `http://localhost/sdv/backend/public/orders/update/${id}`,
        pedido
      )
      .then(() => navigate("/pedidos"))
      .catch((err) => console.error(err));
  }

  return (
    <div>
      <h2 className="text-2xl font-bold mb-4">Editar Pedido</h2>
      <form onSubmit={handleSubmit} className="flex flex-col gap-3 w-96">
        <input
          type="number"
          name="cliente_id"
          placeholder="ID do Cliente"
          value={pedido.cliente_id}
          onChange={handleChange}
          className="border p-2 rounded"
        />
        <input
          type="text"
          name="itens"
          placeholder="Itens"
          value={pedido.itens}
          onChange={handleChange}
          className="border p-2 rounded"
        />
        <input
          type="number"
          name="total"
          placeholder="Total"
          value={pedido.total}
          onChange={handleChange}
          className="border p-2 rounded"
        />
        <button
          type="submit"
          className="px-4 py-2 bg-blue-600 text-white rounded"
        >
          Atualizar
        </button>
      </form>
    </div>
  );
}

export default PedidosAtualizar;
