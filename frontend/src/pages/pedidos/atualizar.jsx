import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import axios from "axios";

function PedidosAtualizar() {
  const { id } = useParams();
  const [pedido, setPedido] = useState(null);
  const [cliente, setCliente] = useState("");
  const [status, setStatus] = useState("");

  useEffect(() => {
    axios.get(`http://sdv.local/orders/get/${id}`)
      .then(res => {
        console.log("Resposta da API:", res.data);
        setPedido(res.data.pedido);
        setCliente(res.data.pedido.cliente);
        setStatus(res.data.pedido.status);
      })
      .catch(err => {
        console.error("Erro ao buscar pedido: ", err);
      });
  }, [id]);

  function handleSubmit(e) {
    e.preventDefault();
    axios.put(`http://sdv.local/orders/${id}`, { cliente, status })
      .then(res => {
        alert("Pedido atualizado com sucesso!");
      })
      .catch(err => {
        console.error("Erro ao atualizar pedido: ", err);
      });
  }

  if (!pedido) {
    return <p>Carregando pedido...</p>;
  }

  return (
    <div>
      <h2>Editar Pedido #{pedido.id}</h2>
      <form onSubmit={handleSubmit}>
        <div>
          <label>Cliente:</label>
          <input
            type="text"
            value={cliente}
            onChange={e => setCliente(e.target.value)}
          />
        </div>
        <div>
          <label>Status:</label>
          <select value={status} onChange={e => setStatus(e.target.value)}>
            <option value="pendente">Pendente</option>
            <option value="aprovado">Aprovado</option>
            <option value="cancelado">Cancelado</option>
          </select>
        </div>
        <button type="submit">Salvar</button>
      </form>
    </div>
  );
}

export default PedidosAtualizar;
