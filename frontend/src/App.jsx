import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import Home from "./pages/home";
import ClientesListar from "./pages/clientes/listar";
import ClientesCriar from "./pages/clientes/criar";
import ClientesAtualizar from "./pages/clientes/atualizar";
import ClientesDeletar from "./pages/clientes/deletar";
import ProdutosListar from "./pages/produtos/listar";
import ProdutosCriar from "./pages/produtos/criar";
import ProdutosAtualizar from "./pages/produtos/atualizar";
import ProdutosDeletar from "./pages/produtos/deletar";
import PedidosListar from "./pages/pedidos/listar";
import PedidosCriar from "./pages/pedidos/criar";
import PedidosAtualizar from "./pages/pedidos/atualizar";
import PedidosDeletar from "./pages/pedidos/deletar";

import Welcome from "./pages/welcome";
import Login from "./pages/login/login";
import Register from "./pages/registro/register";

import "./App.css";

function App() {
  return (
    <Router>
      <Routes>
        {/* Rotas públicas */}
        <Route path="/" element={<Welcome />} />
        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} />

        {/* Rotas do Dashboard */}
        <Route
          path="/dashboard/*"
          element={
            <div className="flex">x
              <main className="container">
                <Routes>
                  <Route path="home" element={<Home />} />

                  <Route path="clientes/listar" element={<ClientesListar />} />
                  <Route path="clientes/criar" element={<ClientesCriar />} />
                  <Route path="clientes/editar/:id" element={<ClientesAtualizar />} />
                  <Route path="clientes/deletar/:id" element={<ClientesDeletar />} />

                  <Route path="produtos/listar" element={<ProdutosListar />} />
                  <Route path="produtos/criar" element={<ProdutosCriar />} />
                  <Route path="produtos/editar/:id" element={<ProdutosAtualizar />} />
                  <Route path="produtos/deletar/:id" element={<ProdutosDeletar />} />

                  <Route path="pedidos/listar" element={<PedidosListar />} />
                  <Route path="pedidos/criar" element={<PedidosCriar />} />
                  <Route path="pedidos/editar/:id" element={<PedidosAtualizar />} />
                  <Route path="pedidos/deletar/:id" element={<PedidosDeletar />} />
                </Routes>
              </main>
            </div>
          }
        />
      </Routes>
    </Router>
  );
}

export default App;