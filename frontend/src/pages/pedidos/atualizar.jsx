import { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import axios from "axios";

function PedidosAtualizar() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [pedido, setPedido] = useState(null);
  const [cliente, setCliente] = useState("");
  const [status, setStatus] = useState("");
  const [itens, setItens] = useState([]);

  useEffect(() => {
    axios.get(`http://sdv.local/orders/get/${id}`)
      .then(res => {
        if (res.data.ok && res.data.pedido) {
          setPedido(res.data.pedido);
          setCliente(res.data.pedido.cliente_id || "");
          setStatus(res.data.pedido.status || "");
          setItens(res.data.pedido.itens || []);
        }
      })
      .catch(err => {
        console.error("Erro ao buscar pedido: ", err);
      });
  }, [id]);

  function handleQuantidadeChange(index, novaQuantidade) {
    const novosItens = [...itens];
    novosItens[index].quantidade = Number(novaQuantidade);
    setItens(novosItens);
  }

  function handleSubmit(e) {
    e.preventDefault();
    axios.put(`http://sdv.local/orders/update/${id}`, {
      cliente_id: cliente,
      status,
      itens,
    })
      .then(res => {
        alert("Pedido atualizado com sucesso!");
        navigate(`/dashboard/pedidos/visualizar/${id}`);
      })
      .catch(err => {
        console.error("Erro ao atualizar pedido: ", err);
      });
  }

  if (!pedido) {
    return <p>Carregando pedido...</p>;
  }

  return (
    <div className="pedidos-container">
      <h2>Editar Pedido #{pedido.id}</h2>
      <form className="pedidos-form" onSubmit={handleSubmit}>
        <div>
          <label>ID do Cliente:</label>
          <input
            type="number"
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

        <h3>Itens do Pedido</h3>
        {itens.map((item, index) => (
          <div key={item.id || index} style={{ marginBottom: "1rem" }}>
            <label>{item.produto_nome || item.nome || "Produto"}:</label>
            <input
              type="number"
              min="0"
              value={item.quantidade}
              onChange={e => handleQuantidadeChange(index, e.target.value)}
            />
          </div>
        ))}

        <button className="btn-submit" type="submit">Salvar</button>
      </form>
    </div>
  );
}

export default PedidosAtualizar;
