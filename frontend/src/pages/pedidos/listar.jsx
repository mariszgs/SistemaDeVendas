import { useEffect, useState } from "react";
import axios from "axios";
import { Link } from "react-router-dom";
import './pedidos.css';

function PedidosListar() {
  const [pedidos, setPedidos] = useState([]);

  useEffect(() => {
    axios.get("http://sdv.local/orders")
      .then(res => {
        if (Array.isArray(res.data.pedidos)) {
          setPedidos(res.data.pedidos);
          console.log('Pedidos:', res.data.pedidos); 
        } else {
          setPedidos([]);
        }
      })
      .catch(err => {
        console.error(err);
        setPedidos([]);
      });
  }, []);

  return (
    <div className="pedidos-container">
      <h2>Pedidos</h2>
      <Link to="/dashboard/pedidos/criar" className="btn-novo">Novo Pedido</Link>

      <table className="pedidos-tabela">
        <thead>
          <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Data</th>
            <th>Status</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          {pedidos.map(pedido => (
            <tr key={pedido.id}>
              <td>{pedido.id}</td>
              <td>{pedido.cliente}</td>
              <td>{pedido.data_pedido ? new Date(pedido.data_pedido).toLocaleDateString() : ''}</td>
              <td>{pedido.status}</td>
              <td>
                <Link to={`/dashboard/pedidos/visualizar/${pedido.id}`} className="btn-visualizar">Visualizar</Link>
                <Link to={`/dashboard/pedidos/editar/${pedido.id}`} className="btn-editar">Editar</Link>
                <Link to={`/dashboard/pedidos/deletar/${pedido.id}`} className="btn-deletar">Deletar</Link>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}

export default PedidosListar;
