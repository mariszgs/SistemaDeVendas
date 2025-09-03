import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Sidebar from './components/sidebar';
import Header from './components/header';
import Home from './pages/home';
import ClientesListar from './pages/clientes/listar';
import ClientesCriar from './pages/clientes/criar';
import ProdutosListar from './pages/produtos/listar';
import ProdutosCriar from './pages/produtos/criar';
import PedidosListar from './pages/pedidos/listar';
import PedidosCriar from './pages/pedidos/criar';

function App() {
  return (
    <Router>
      <div className="flex">
        <Sidebar />
        <div className="flex-1">
          <Header />
          <main className="p-6">
            <Routes>
              <Route path="/" element={<Home />} />
              <Route path="/clientes/listar" element={<ClientesListar />} />
              <Route path="/clientes/criar" element={<ClientesCriar />} />
              <Route path="/produtos/listar" element={<ProdutosListar />} />
              <Route path="/produtos/criar" element={<ProdutosCriar />} />
              <Route path="/pedidos/listar" element={<PedidosListar />} />
              <Route path="/pedidos/criar" element={<PedidosCriar />} />
            </Routes>
          </main>
        </div>
      </div>
    </Router>
  );
}

export default App;
