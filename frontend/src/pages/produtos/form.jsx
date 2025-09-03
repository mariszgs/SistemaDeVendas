import { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import axios from "axios";


function ProdutosForm() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [produto, setProduto] = useState({
    nome: "",
    descricao: "",
    preco: "",
    estoque: "",
  });

  useEffect(() => {
    if (id) {
      axios
        .get(`http://localhost/sdv/backend/public/products/${id}`)
        .then((res) => setProduto(res.data.produto))
        .catch((err) => console.error(err));
    }
  }, [id]);

  function handleChange(e) {
    setProduto({ ...produto, [e.target.name]: e.target.value });
  }

  function handleSubmit(e) {
    e.preventDefault();
    const url = id
      ? `http://localhost/sdv/backend/public/products/update/${id}`
      : "http://localhost/sdv/backend/public/products/";

    axios
      .post(url, produto)
      .then(() => navigate("/produtos"))
      .catch((err) => console.error(err));
  }

  return (
    <div>
      <h2 className="text-2xl font-bold mb-4">
        {id ? "Editar Produto" : "Novo Produto"}
      </h2>
      <form onSubmit={handleSubmit} className="flex flex-col gap-3 w-96">
        <input
          type="text"
          name="nome"
          placeholder="Nome"
          value={produto.nome}
          onChange={handleChange}
          className="border p-2 rounded"
        />
        <textarea
          name="descricao"
          placeholder="Descrição"
          value={produto.descricao}
          onChange={handleChange}
          className="border p-2 rounded"
        />
        <input
          type="number"
          name="preco"
          placeholder="Preço"
          value={produto.preco}
          onChange={handleChange}
          className="border p-2 rounded"
        />
        <input
          type="number"
          name="estoque"
          placeholder="Estoque"
          value={produto.estoque}
          onChange={handleChange}
          className="border p-2 rounded"
        />
        <button
          type="submit"
          className="px-4 py-2 bg-green-600 text-white rounded"
        >
          Salvar
        </button>
      </form>
    </div>
  );
}

export default ProdutosForm;
