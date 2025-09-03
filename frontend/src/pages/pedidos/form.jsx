import { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import axios from "axios";

function PedidosForm() {
  const [clienteId, setClienteId] = useState("");
  const [total, setTotal] = useState("");
  const navigate = useNavigate();
  const { id } = useParams(); // se existir, estamos editando

  // Se for edição, busca os dados do pedido
  useEffect(() => {
    if (id) {
      fetch(`http://sdv.local/pedidos/${id}`)
        .then(res => res.json())
        .then(data => {
          setClienteId(data.cliente_id);
          setTotal(data.total);
        });
    }
  }, [id]);

  // Salvar pedido
  const salvarPedido = async (e) => {
    e.preventDefault();
    const pedido = { cliente_id: clienteId, total };

    const method = id ? "PUT" : "POST";
    const url = id
      ? `http://sdv.local/pedidos/${id}`
      : `http://sdv.local/pedidos`;

    await fetch(url, {
      method,
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(pedido)
    });

    navigate("/pedidos"); // volta pra listagem
  };

  return (
    <div>
      <h2>{id ? "✏️ Editar Pedido" : "➕ Novo Pedido"}</h2>
      <form onSubmit={salvarPedido}>
        <div>
          <label>Cliente ID:</label>
          <input
            type="text"
            value={clienteId}
            onChange={e => setClienteId(e.target.value)}
            required
          />
        </div>
        <div>
          <label>Total:</label>
          <input
            type="number"
            value={total}
            onChange={e => setTotal(e.target.value)}
            required
          />
        </div>
        <button type="submit">💾 Salvar</button>
      </form>
    </div>
  );
}

export default PedidosForm;
