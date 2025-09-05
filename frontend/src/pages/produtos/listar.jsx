import { useEffect, useState } from "react";
import axios from "axios";
import { Link } from "react-router-dom";
import './produtos.css';

function ProdutosListar() {
  const [produtos, setProdutos] = useState([]);

  useEffect(() => {
    axios.get("http://sdv.local/products")
      .then(res => setProdutos(res.data.products))
      .catch(err => console.error(err));
  }, []);

  return (
    <div className="produtos-container">
      <h2>Produtos</h2>
      <Link to="/dashboard/produtos/criar" className="novo-produto-btn">Novo Produto</Link>

      <table className="produtos-tabela">
        <thead>
          <tr>
            <th>Nome</th>
            <th>Preço</th>
            <th>Estoque</th> 
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          {produtos.map(produto => (
            <tr key={produto.id}>
              <td>{produto.nome}</td>
              <td>{produto.preco}</td>
              <td>{produto.estoque}</td> 
              <td>
                <Link to={`/dashboard/produtos/editar/${produto.id}`} className="btn-editar">Editar</Link>
                <Link to={`/dashboard/produtos/deletar/${produto.id}`} className="btn-deletar">Deletar</Link>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}

export default ProdutosListar;
