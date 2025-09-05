import { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import axios from "axios";
import './clientes.css'; 

function ClientesAtualizar() {
  const { id } = useParams();
  const navigate = useNavigate();
  
  const [cliente, setCliente] = useState({
    nome: "",
    email: "",
    cnpj: "",
    telefone: "",
    endereco: ""
  });

  useEffect(() => {
    axios
      .get(`http://sdv.local/clients/${id}`)
      .then((res) => setCliente(res.data.client))
      .catch((err) => console.error(err));
  }, [id]);

  function handleChange(e) {
    setCliente({ ...cliente, [e.target.name]: e.target.value });
  }

  function handleSubmit(e) {
    e.preventDefault();
    axios
      .post(`http://sdv.local/clients/update/${id}`, cliente)
      .then(() => navigate("/dashboard/clientes"))
      .catch((err) => console.error(err));
  }

  return (
    <div className="clientes-container">
      <h2 className="form-titulo">Editar Cliente</h2>

      <form onSubmit={handleSubmit} className="clientes-form">
        <input
          type="text"
          name="nome"
          placeholder="Nome"
          value={cliente.nome}
          onChange={handleChange}
          className="form-input"
        />
        <input
          type="email"
          name="email"
          placeholder="Email"
          value={cliente.email}
          onChange={handleChange}
          className="form-input"
        />
        <input
          type="text"
          name="cnpj"
          placeholder="CNPJ"
          value={cliente.cnpj}
          onChange={handleChange}
          className="form-input"
        />
        <input
          type="text"
          name="telefone"
          placeholder="Telefone"
          value={cliente.telefone}
          onChange={handleChange}
          className="form-input"
        />
        <input
          type="text"
          name="endereco"
          placeholder="Endereço"
          value={cliente.endereco}
          onChange={handleChange}
          className="form-input"
        />

        <button type="submit" className="form-botao">
          Atualizar
        </button>
      </form>
    </div>
  );
}

export default ClientesAtualizar;
