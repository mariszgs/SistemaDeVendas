import { BrowserRouter as Router, Routes, Route, Link } from "react-router-dom";
import Home from "./pages/Home";
import ClientesListar from "./pages/Clientes/Listar";
import ClientesCriar from "./pages/Clientes/Criar";
import ProdutosListar from "./pages/Produtos/Listar";
import PedidosListar from "./pages/Pedidos/Listar";
import Relatorios from "./pages/Relatorios";

function App() {
  return (
    <Router>
      <div className="p-6">
        <nav className="flex gap-4 mb-6">
          <Link to="/"> Home</Link>
          <Link to="/clientes"> Clientes</Link>
          <Link to="/produtos"> Produtos</Link>
          <Link to="/pedidos"> Pedidos</Link>
          <Link to="/relatorios"> Relatórios</Link>
        </nav>

        <Routes>
          <Route path="/" element={<Home />} />

          <Route path="/clientes" element={<ClientesListar />} />
          <Route path="/clientes/novo" element={<ClientesCriar />} />

          <Route path="/produtos" element={<ProdutosListar />} />

          <Route path="/pedidos" element={<PedidosListar />} />

          <Route path="/relatorios" element={<Relatorios />} />
        </Routes>
      </div>
    </Router>
  );
}

export default App;
