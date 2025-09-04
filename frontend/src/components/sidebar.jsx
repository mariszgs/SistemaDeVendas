import { Link } from 'react-router-dom';
import './Sidebar.css'; 

function Sidebar() {
  return (
    <aside className="sidebar">
        <h1 className="sidebar-title">
        <Link to="/" style={{ color: 'inherit', textDecoration: 'none' }}>
          Sistema de Vendas
        </Link>
      </h1>
      <nav>
        <Link to="/clientes/listar">Clientes</Link>
        <Link to="/produtos/listar">Produtos</Link>
        <Link to="/pedidos/listar">Pedidos</Link>
      </nav>
    </aside>
  );
}

export default Sidebar;
