import { useEffect, useState } from "react";
import { useParams, Link } from "react-router-dom";
import axios from "axios";
import './pedidos.css';

function PedidosVisualizar() {
  const { id } = useParams();
  const [pedido, setPedido] = useState(null);
  const [notaFiscal, setNotaFiscal] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    setLoading(true); // Garante loading no começo da requisição
    axios.get(`http://sdv.local/orders/get/${id}`)
      .then(res => {
        if (res.data.ok && res.data.pedido) {
          setPedido(res.data.pedido);
          setNotaFiscal(res.data.nota_fiscal || null);
        } else {
          alert("Pedido não encontrado");
          setPedido(null);
          setNotaFiscal(null);
        }
      })
      .catch(err => {
        console.error("Erro ao buscar pedido:", err);
        alert("Erro ao buscar pedido");
        setPedido(null);
        setNotaFiscal(null);
      })
      .finally(() => setLoading(false));
  }, [id]);

  if (loading) return <p>Carregando pedido...</p>;
  if (!pedido) return <p>Pedido não encontrado.</p>;

  return (
    <div className="pedidos-container">
      <h2>Visualizar Pedido #{pedido.id}</h2>

      <section>
        <h3>Dados do Pedido</h3>
        <p><strong>Cliente ID:</strong> {pedido.cliente_id}</p>
        <p><strong>Status:</strong> {pedido.status}</p>
        <p><strong>Total:</strong> R$ {(Number(pedido.total) || 0).toFixed(2)}</p>
        <p><strong>Criado em:</strong> {pedido.criado_em || 'Não informado'}</p>
        <p><strong>Usuário:</strong> {pedido.usuario || 'Não informado'}</p>
      </section>

      <section>
        <h3>Itens do Pedido</h3>
        {pedido.itens && pedido.itens.length > 0 ? (
          <table className="pedidos-tabela">
            <thead>
              <tr>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Preço Unitário</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody>
              {pedido.itens.map(item => (
                <tr key={item.id || item.produto_id}>
                  <td>{item.produto_nome || item.nome || 'Produto'}</td>
                  <td>{item.quantidade}</td>
                  <td>R$ {Number(item.preco_unitario).toFixed(2)}</td>
                  <td>R$ {(item.quantidade * item.preco_unitario).toFixed(2)}</td>
                </tr>
              ))}
            </tbody>
          </table>
        ) : (
          <p>Este pedido não possui itens.</p>
        )}
      </section>

      {notaFiscal && notaFiscal.nfe ? (
        <section className="nota-fiscal-section">
          <h3>Nota Fiscal</h3>
          <p><strong>Chave:</strong> {notaFiscal.nfe.chave || 'Não informado'}</p>
          <p><strong>Número:</strong> {notaFiscal.nfe.numero || 'Não informado'}</p>
          <p><strong>Série:</strong> {notaFiscal.nfe.serie || 'Não informado'}</p>
          <p><strong>Valor Total:</strong> R$ {Number(notaFiscal.nfe.valor_total).toFixed(2)}</p>
          <p><strong>Quantidade de Itens:</strong> {notaFiscal.nfe.qtd_itens}</p>
          {notaFiscal.nfe.url_xml ? (
            <p><a href={notaFiscal.nfe.url_xml} target="_blank" rel="noopener noreferrer">Download XML</a></p>
          ) : <p>XML não disponível</p>}
          {notaFiscal.nfe.url_danfe ? (
            <p><a href={notaFiscal.nfe.url_danfe} target="_blank" rel="noopener noreferrer">Download DANFE</a></p>
          ) : <p>DANFE não disponível</p>}
        </section>
      ) : (
        <p>Nota fiscal não disponível para este pedido.</p>
      )}

      <Link to="/dashboard/pedidos/listar" className="btn-novo" style={{ marginTop: '1rem' }}>Voltar</Link>
    </div>
  );
}

export default PedidosVisualizar;
