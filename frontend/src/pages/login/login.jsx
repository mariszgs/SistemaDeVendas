import { useState } from "react";
import { useNavigate } from "react-router-dom";
import "../login.css";

function Login() {
  const [email, setEmail] = useState("");
  const [senha, setSenha] = useState("");
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      const response = await fetch("http://sdv.local/login", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, senha }),
        credentials: "include", // garante cookies/sessão do Laminas
      });

      if (!response.ok) throw new Error("Credenciais inválidas!");

      // Se deu certo, vai direto pro dashboard
      navigate("/dashboard/home");
    } catch (err) {
      alert(err.message);
    }
  };

  return (
    <div className="login-container">
      <h2>Entrar</h2>
      <form onSubmit={handleSubmit}>
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
        <button type="submit">Login</button>
      </form>
    </div>
  );
}

export default Login;
