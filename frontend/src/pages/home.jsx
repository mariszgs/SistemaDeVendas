import { Link } from 'react-router-dom';
import './Home.css';

function Home() {
  return (
    <div className="home-container">
      <header className="home-header">
        <h2>Sistema de Vendas</h2>
      </header>

      <main className="home-content">
        <div className="card">
          <p>Você está logado!</p>

          <div className="button-group">
            <Link to="/dashboard/clientes/listar" className="btn">Clientes</Link>
            <Link to="/dashboard/produtos/listar" className="btn">Produtos</Link>
            <Link to="/dashboard/pedidos/listar" className="btn">Pedidos</Link>
          </div>
        </div>
      </main>
    </div>
  );
}

export default Home;
