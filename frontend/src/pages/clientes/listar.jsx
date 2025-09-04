import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import axios from "axios";
import './clientes.css';

function ClientesListar() {
  const [clientes, setClientes] = useState([]);

  useEffect(() => {
    axios
      .get("http://sdv.local/clients?format=json")
      .then((res) => {
      console.log('Resposta do backend:', res.data);
      setClientes(res.data.clients); // aqui é clients, não client
    })
      .catch((err) => console.error(err));
  }, []);

  function handleDelete(id) {
    if (!window.confirm("Tem certeza que deseja deletar?")) return;

    axios
      .delete(`http://sdv.local/clients/delete/${id}`)
      .then(() => setClientes(clientes.filter((c) => c.id !== id)))
      .catch((err) => console.error(err));
  }

  return (
  <div className="clientes-container">
    <h2>Clientes</h2>
    <Link to="/clientes/criar" className="novo-cliente-btn">
      Novo Cliente
    </Link>
    <table className="clientes-tabela">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Email</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        {clientes.map((c) => (
          <tr key={c.id}>
            <td>{c.nome}</td>
            <td>{c.email}</td>
            <td className="acao-container">
              <Link
                to={`/clientes/editar/${c.id}`}
                className="btn-editar"
              >
                Editar
              </Link>
              <button
                onClick={() => handleDelete(c.id)}
                className="btn-deletar"
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

export default ClientesListar;
