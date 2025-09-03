import { Link } from 'react-router-dom';

function Sidebar() {
  return (
    <aside className="w-64 h-screen bg-gray-800 text-white p-4">
      <h1 className="text-xl font-bold mb-6">Sistema de Vendas</h1>
      <nav className="flex flex-col gap-3">
        <Link to="/clientes/listar" className="hover:underline">Clientes</Link>
        <Link to="/produtos/listar" className="hover:underline">Produtos</Link>
        <Link to="/pedidos/listar" className="hover:underline">Pedidos</Link>
      </nav>
    </aside>
  );
}

export default Sidebar;
