import { useEffect } from "react";
import { useNavigate, useParams } from "react-router-dom";
import axios from "axios";

function ProdutosDeletar() {
  const { id } = useParams(); // pega o ID da URL
  const navigate = useNavigate();

  useEffect(() => {
    const confirmar = window.confirm("Tem certeza que deseja deletar este produto?");
    if (!confirmar) {
      navigate("/dashboard/produtos/listar");
      return;
    }

    axios.delete(`http://sdv.local/products/${id}`)
      .then(() => {
        alert("Produto deletado com sucesso!");
        navigate("/dashboard/produtos/listar");
      })
      .catch((err) => {
        console.error("Erro ao deletar produto:", err);
        alert("Erro ao deletar o produto.");
        navigate("/dashboard/produtos/listar");
      });
  }, [id, navigate]);

  return (
    <div style={{ padding: "2rem", textAlign: "center" }}>
      <p>Deletando produto...</p>
    </div>
  );
}

export default ProdutosDeletar;
