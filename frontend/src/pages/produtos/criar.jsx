import { useState } from "react";
import { useNavigate } from "react-router-dom";
import axios from "axios";
import './produtos.css';

function ProdutosCriar() {
  const navigate = useNavigate();
  const [produto, setProduto] = useState({
    nome: "",
    preco: "",
    estoque: "", 
  });

  function handleChange(e) {
    setProduto({...produto, [e.target.name]: e.target.value });
  }

  function handleSubmit(e) {
    e.preventDefault();
    axios.post("http://sdv.local/products/", produto)
      .then(() => navigate("/dashboard/produtos/listar"))
      .catch(err => console.error(err));
  }

  return (
    <div className="produtos-container">
      <h2>Criar Produto</h2>
      <form onSubmit={handleSubmit} className="produtos-form">
        <input
          type="text"
          name="nome"
          placeholder="Nome"
          value={produto.nome}
          onChange={handleChange}
          className="form-input"
          required
        />
        <input
          type="number"
          name="preco"
          placeholder="Preço"
          value={produto.preco}
          onChange={handleChange}
          className="form-input"
          step="0.01"
          required
        />
        <input
          type="number"
          name="estoque"
          placeholder="Estoque"
          value={produto.estoque}
          onChange={handleChange}
          className="form-input"
          min="0"
          required
        />
        <button type="submit" className="form-botao">Salvar</button>
      </form>
    </div>
  );
}

export default ProdutosCriar;
