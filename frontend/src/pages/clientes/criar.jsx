import { useState } from "react";
import { useNavigate } from "react-router-dom";
import axios from "axios";
import './clientes.css';

function ClientesCriar() {
  const navigate = useNavigate();
  const [cliente, setCliente] = useState({
    nome: "",
    email: "",
    cnpj: "",
    telefone: "",
    endereco: ""
  });

  function handleChange(e) {
    setCliente({ ...cliente, [e.target.name]: e.target.value });
  }

  function handleSubmit(e) {
    e.preventDefault();
    axios
      .post("http://sdv.local/clients/create", cliente)
      .then(() => navigate("/dashboard/clientes/listar"))
      .catch((err) => console.error(err));
  }

  return (
    <div className="clientes-container">
      <h2 className="form-titulo">Novo Cliente</h2>
      <form onSubmit={handleSubmit} className="clientes-form">
        <input
          type="text"
          name="nome"
          placeholder="Nome"
          value={cliente.nome}
          onChange={handleChange}
          className="form-input"
          required
        />
        <input
          type="email"
          name="email"
          placeholder="Email"
          value={cliente.email}
          onChange={handleChange}
          className="form-input"
          required
        />
        <input
          type="text"
          name="cnpj"
          placeholder="CNPJ"
          value={cliente.cnpj}
          onChange={handleChange}
          className="form-input"
          required
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
          Salvar
        </button>
      </form>
    </div>
  );
}

export default ClientesCriar;
