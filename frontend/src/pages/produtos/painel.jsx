import { useNavigate } from "react-router-dom";

function ProdutosPainel() {
  const navigate = useNavigate();

  return (
    <div>
      <h1 className="text-2xl font-bold mb-4">Painel de Produtos</h1>
      <div className="flex flex-col gap-2">
        <button
          onClick={() => navigate("/produtos")}
          className="px-4 py-2 bg-blue-600 text-white rounded"
        >
          Listar
        </button>
        <button
          onClick={() => navigate("/produtos/criar")}
          className="px-4 py-2 bg-green-600 text-white rounded"
        >
          Criar
        </button>
      </div>
    </div>
  );
}

export default ProdutosPainel;
