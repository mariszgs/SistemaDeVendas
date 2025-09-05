import { useEffect } from "react";
import { useNavigate, useParams } from "react-router-dom";
import axios from "axios";

function PedidosDeletar() {
  const { id } = useParams();
  const navigate = useNavigate();

  useEffect(() => {
    if(window.confirm("Tem certeza que deseja deletar este pedido?")) {
      axios.delete(`http://sdv.local/orders/${id}`)
        .then(() => navigate("/dashboard/pedidos/listar"))
        .catch(err => {
          alert("Erro ao deletar pedido.");
          navigate("dashboard/pedidos/listar");
        });
    } else {
      navigate("/dashboard/pedidos/listar");
    }
  }, [id, navigate]);

  return null;
}

export default PedidosDeletar;
