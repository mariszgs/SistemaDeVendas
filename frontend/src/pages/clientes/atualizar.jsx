import { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import axios from "axios";

function ClientesAtualizar() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [cliente, setCliente] = useState({
    nome: "",
    email: "",
  });

  useEffect(() => {
    axios
      .get(`http://localhost/sdv/backend/public/clients/${id}`)
      .then((res) => setCliente(res.data.cliente))
      .catch((err) => console.error(err));
  }, [id]);

  function handleChange(e) {
    setCliente({ ...cliente, [e.target.name]: e.target.value });
  }

  function handleSubmit(e) {
    e.preventDefault();
    axios
      .post(
        `http://localhost/sdv/backend/public/clients/update/${id}`,
        cliente
      )
      .then(() => navigate("/clientes"))
      .catch((err) => console.error(err));
  }

  return (
    <div>
      <h2 className="text-2xl font-bold mb-4">Editar Cliente</h2>
      <form onSubmit={handleSubmit} className="flex flex-col gap-3 w-96">
        <input
          type="text"
          name="nome"
          placeholder="Nome"
          value={cliente.nome}
          onChange={handleChange}
          className="border p-2 rounded"
        />
        <input
          type="email"
          name="email"
          placeholder="Email"
          value={cliente.email}
          onChange={handleChange}
          className="border p-2 rounded"
        />
        <button
          type="submit"
          className="px-4 py-2 bg-blue-600 text-white rounded"
        >
          Atualizar
        </button>
      </form>
    </div>
  );
}

export default ClientesAtualizar;
