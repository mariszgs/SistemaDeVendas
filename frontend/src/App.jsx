import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Sidebar from './components/Sidebar';
import Home from './pages/Home';
import ClientesListar from './pages/clientes/listar';
import ClientesCriar from './pages/clientes/criar';
import ClientesAtualizar from './pages/clientes/atualizar';
import ClientesDeletar from './pages/clientes/deletar';
import ProdutosListar from './pages/produtos/listar';
import ProdutosCriar from './pages/produtos/criar';
import ProdutosAtualizar from './pages/produtos/atualizar';
import PedidosListar from './pages/pedidos/listar';
import PedidosCriar from './pages/pedidos/criar';
import PedidosAtualizar from './pages/pedidos/atualizar';
import PedidosDeletar from './pages/pedidos/deletar';

import './App.css'; 

function App() {
  return (
    <Router>
      <div className="flex">
        <Sidebar />
        <main className="container">
          <Routes>
            <Route path="/" element={<Home />} />

            <Route path="/clientes/listar" element={<ClientesListar />} />
            <Route path="/clientes/criar" element={<ClientesCriar />} />
            <Route path="/clientes/editar/:id" element={<ClientesAtualizar />} />
            <Route path="/clientes/deletar/:id" element={<ClientesDeletar />} />

            <Route path="/produtos/listar" element={<ProdutosListar />} />
            <Route path="/produtos/criar" element={<ProdutosCriar />} />
            <Route path="/produtos/editar/:id" element={<ProdutosAtualizar />} />

            <Route path="/pedidos/listar" element={<PedidosListar />} />
            <Route path="/pedidos/criar" element={<PedidosCriar />} />
            <Route path="/pedidos/deletar/:id" element={<PedidosDeletar />} />
            <Route path="/pedidos/editar/:id" element={<PedidosAtualizar />} />
          </Routes>
        </main>
      </div>
    </Router>
  );
}

export default App;
