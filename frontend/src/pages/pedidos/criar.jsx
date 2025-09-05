  import { useState } from "react";
  import axios from "axios";
  import { useNavigate } from "react-router-dom";
  import './pedidos.css';

  function PedidosCriar() {
    const navigate = useNavigate();

    const [clienteId, setClienteId] = useState("");
    const [status, setStatus] = useState("pendente");
    const [itens, setItens] = useState([
      { produto_id: "", quantidade: 1, preco_unitario: 0 }
    ]);

    const adicionarItem = () => {
      setItens([...itens, { produto_id: "", quantidade: 1, preco_unitario: 0 }]);
    };

    const atualizarItem = (index, campo, valor) => {
      const novosItens = [...itens];
      novosItens[index][campo] = valor;
      setItens(novosItens);
    };

    const removerItem = (index) => {
      const novosItens = [...itens];
      novosItens.splice(index, 1);
      setItens(novosItens);
    };

    const handleSubmit = async (e) => {
      e.preventDefault();
      try {
        const response = await axios.post("http://sdv.local/orders", {
          cliente_id: clienteId,
          status,
          itens
        });

        const { nota_fiscal, pedido } = response.data;

        alert("Pedido criado com sucesso!");

        console.log("Pedido:", pedido);
        console.log("Nota fiscal:", nota_fiscal);

        navigate("/dashboard/pedidos/listar");
      } catch (err) {
        console.error("Erro ao criar pedido:", err);
        alert("Erro ao criar pedido. Verifique os dados.");
      }
    };

    return (
      <form className="pedidos-form" onSubmit={handleSubmit}>
        <h2>Criar Pedido</h2>

        <input
          type="number"
          placeholder="ID do Cliente"
          value={clienteId}
          onChange={(e) => setClienteId(e.target.value)}
          required
        />

        <select value={status} onChange={(e) => setStatus(e.target.value)} required>
          <option value="pendente">Pendente</option>
          <option value="em andamento">Em andamento</option>
          <option value="finalizado">Finalizado</option>
        </select>

        <h3>Itens do Pedido</h3>
        {itens.map((item, index) => (
          <div key={index} className="item-linha">
            <input
              type="number"
              placeholder="ID do Produto"
              value={item.produto_id}
              onChange={(e) => atualizarItem(index, "produto_id", e.target.value)}
              required
            />
            <input
              type="number"
              placeholder="Quantidade"
              value={item.quantidade}
              onChange={(e) => atualizarItem(index, "quantidade", e.target.value)}
              required
            />
            <input
              type="number"
              placeholder="Preço Unitário"
              step="0.01"
              value={item.preco_unitario}
              onChange={(e) => atualizarItem(index, "preco_unitario", e.target.value)}
              required
            />
            <button type="button" onClick={() => removerItem(index)}>Remover</button>
          </div>
        ))}

        <button type="button" onClick={adicionarItem}>+ Adicionar Item</button>
        <br />
        <button type="submit" className="btn-submit">Salvar Pedido</button>
      </form>
    );
  }

  export default PedidosCriar;
