import { useState } from "react";
import { useNavigate } from "react-router-dom";
import "../login.css";

function Register() {
  const [nome, setNome] = useState("");
  const [email, setEmail] = useState("");
  const [senha, setSenha] = useState("");
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      const response = await fetch("http://sdv.local/register", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ nome, email, senha }),
        credentials: "include",
      });

      if (!response.ok) throw new Error("Erro ao registrar usuário!");

      alert("Cadastro realizado com sucesso!");
      navigate("/dashboard/home");
    } catch (err) {
      alert(err.message);
    }
  };

  return (
    <div className="login-container">
      <h2>Registrar</h2>
      <form onSubmit={handleSubmit}>
        <input
          type="text"
          placeholder="Nome"
          value={nome}
          onChange={(e) => setNome(e.target.value)}
          required
        />
        <input
          type="email"
          placeholder="E-mail"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          required
        />
        <input
          type="password"
          placeholder="Senha"
          value={senha}
          onChange={(e) => setSenha(e.target.value)}
          required
        />
        <button type="submit">Registrar</button>
      </form>
    </div>
  );
}

export default Register;
