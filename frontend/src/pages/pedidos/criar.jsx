import { useState } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";
import './pedidos.css';

function PedidosCriar() {
  const [clienteNome, setClienteNome] = useState("");
  const [data, setData] = useState("");
  const [status, setStatus] = useState("");
  const navigate = useNavigate();

  function handleSubmit(e) {
    e.preventDefault();
    axios.post("http://sdv.local/orders", { clienteNome, data, status })
      .then(() => navigate("/pedidos/listar"))
      .catch(err => console.error(err));
  }

  return (
    <form className="pedidos-form" onSubmit={handleSubmit}>
      <h2>Novo Pedido</h2>

      <input
        type="text"
        placeholder="Nome do Cliente"
        value={clienteNome}
        onChange={e => setClienteNome(e.target.value)}
        required
      />

      <input
        type="date"
        value={data}
        onChange={e => setData(e.target.value)}
        required
      />

      <select value={status} onChange={e => setStatus(e.target.value)} required>
        <option value="">Selecione o status</option>
        <option value="pendente">Pendente</option>
        <option value="em andamento">Em andamento</option>
        <option value="finalizado">Finalizado</option>
      </select>

      <button type="submit" className="btn-submit">Salvar</button>
    </form>
  );
}

export default PedidosCriar;
