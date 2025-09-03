import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import axios from "axios";

function ProdutosListar() {
  const [produtos, setProdutos] = useState([]);

  useEffect(() => {
    axios
      .get("http://localhost/sdv/backend/public/products")
      .then((res) => setProdutos(res.data.produtos))
      .catch((err) => console.error(err));
  }, []);

  function handleDelete(id) {
    if (!window.confirm("Tem certeza que deseja deletar?")) return;

    axios
      .delete(`http://localhost/sdv/backend/public/products/delete/${id}`)
      .then(() => setProdutos(produtos.filter((p) => p.id !== id)))
      .catch((err) => console.error(err));
  }

  return (
    <div>
      <h2 className="text-2xl font-bold mb-4">Produtos</h2>
      <Link
        to="/produtos/criar"
        className="px-4 py-2 bg-green-600 text-white rounded"
      >
        Novo Produto
      </Link>
      <table className="w-full mt-4 border">
        <thead>
          <tr className="bg-gray-200">
            <th className="p-2">Nome</th>
            <th className="p-2">Preço</th>
            <th className="p-2">Estoque</th>
            <th className="p-2">Ações</th>
          </tr>
        </thead>
        <tbody>
          {produtos.map((p) => (
            <tr key={p.id} className="border-t">
              <td className="p-2">{p.nome}</td>
              <td className="p-2">R$ {p.preco}</td>
              <td className="p-2">{p.estoque}</td>
              <td className="p-2 flex gap-2">
                <Link
                  to={`/produtos/editar/${p.id}`}
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

export default ProdutosListar;
