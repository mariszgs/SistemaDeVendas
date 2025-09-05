import { useEffect, useState } from "react";
import { useParams, useNavigate, Link } from "react-router-dom";
import axios from "axios";
import './clientes.css'; // CSS compartilhado

function ClientesDeletar() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [cliente, setCliente] = useState(null);

  useEffect(() => {
    axios
      .get(`http://sdv.local/clients/${id}`)
      .then((res) => setCliente(res.data.client))
      .catch((err) => console.error(err));
  }, [id]);

  function handleDelete() {
    axios
      .delete(`http://sdv.local/clients/delete/${id}`)
      .then(() => navigate("/dashboard/clientes"))
      .catch((err) => console.error(err));
  }

  if (!cliente) return <p>Carregando cliente...</p>;

  return (
    <div className="clientes-container">
      <h2 className="form-titulo">Excluir Cliente</h2>
      <p className="form-texto">
        Tem certeza que deseja excluir o cliente <strong>{cliente.nome}</strong>?
      </p>

      <div className="btn-deletar-container">
        <button onClick={handleDelete} className="form-botao-deletar">
          Sim, excluir
        </button>
        <Link to="/dashboard/clientes" className="form-botao-cancelar">
          Cancelar
        </Link>
      </div>
    </div>
  );
}

export default ClientesDeletar;
