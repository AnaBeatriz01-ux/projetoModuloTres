"use strict";

// -- busca os dados/produtos na API, calcula o faturamento(reduce) e escreve na tela.
// -- guarda a ultima lista carregada da API, assim o filtro de categoria n precisa buscar
// -- td denovo quando o usuario troca o select

let produtosCarregados = [];

// -- busca os dados, async pq o fetch demora (chamada de rede)
// -- n trava a página esperando

async function carregarDashboard() {
    try {
        const resposta = await fetch('../api/dashboard.php');
        // resposta.ok só é false se o SERVIDOR respondeu com erro
        
        // não trata isso como erro automático, só rede caindo mesmo —
        // por isso preciso checar isso na mão aqui
        if (!resposta.ok) {
            throw new Error(`API respondeu com erro. Status: ${resposta.status}`);
        }
        const produtos = await resposta.json();
        produtosCarregados = produtos;
        popularFiltroCategorias(produtos);
        renderizarDestaques(produtos);
        renderizarDashboard(produtos);
    }
    catch (erro) {
        // cai aqui se: rede caiu, api não achou o arquivo, banco fora
        // do ar, ou o json veio bugado. mostra aviso na tela em vez de
        // deixar tudo travado em "Carregando..." pra sempre
        console.error('Erro ao carregar o dashboard:', erro);
        mostrarErroNaTela();
    }
}
// soma preço x vendas de cada produto = faturamento total
function calcularFaturamentoTotal(produtos) {
    // se não tem produto nenhum, não tem faturamento — sem esse if o
    // reduce ainda funcionaria (dava 0 igual)
    if (produtos.length === 0) {
        return 0;
    }
    return produtos.reduce((total, produto) => {
        const precoUnitario = Number(produto.preco); // converte de string pra numero
        const faturamentoDesseProduto = precoUnitario * produto.vendas;
        return total + faturamentoDesseProduto;
    }, 0);

}

// -- usa o .map(), um item de entrada vira de saída já formatado
function mapearParaExibicao(produtos) {
    return produtos.map((produto) => {
        const precoNumero = Number(produto.preco);
        const faturamentoBruto = precoNumero * produto.vendas;
        return {
            id: produto.id,
            nome: produto.nome,
            categoriaNome: produto.categoria_nome,
            statusEstoque: produto.status_estoque,
            precoFormatado: formatarMoeda(precoNumero),
            vendas: produto.vendas,
            faturamentoFormatado: formatarMoeda(faturamentoBruto),
            faturamentoBruto,
        };
    });

    // -- classe badge pra estética

    function classeBadgeStatus(status) {
    if (status === 'Esgotado')
        return 'bg-danger';
    if (status === 'Estoque baixo')
        return 'bg-warning text-dark';
    return 'bg-success';
}

    // -- preenche o select de categoria a partir dos produtos q 
    // -- ja vieram, sem precisar de uma segunda chamada na API.

    function popularFiltroCategorias(produtos) {
        const selectCategoria = document.getElementById('filtro-categoria');
        if (!select)
            return;
        // -- tira os nomes repetidos pra n sobrepor categoria
        const categorias = Array.from(new Set(produtos.map((p) => p.categoria_nome))).sort();
   
         select.innerHTML = '';
         const opcaoTodas = document.createElement('option');
         opcaoTodas.value = '';
            opcaoTodas.textContent = 'Todas as categorias';
            select.appendChild(opcaoTodas);
            categorias.forEach((nomeCategoria) => {
                const opcao = document.createElement('option');
                opcao.value = nomeCategoria;
                opcao.textContent = nomeCategoria;
                select.appendChild(opcao);
            });
            select.addEventListener('change', () => {
                aplicarFiltroCategoria(select.value);
            });
    }

    // -- .filter(): pra separar array completo e ter só os arrays da categoria escolhida
    function aplicarFiltroCategoria(categoriaEscolhida) {
    const filtrados = categoriaEscolhida === ''
        ? produtosCarregados
        : produtosCarregados.filter((produto) => produto.categoria_nome === categoriaEscolhida);
    renderizarDashboard(filtrados);
}

    // -- destaca os 3 produtos mais vendidos, do maior pra menor
    // -- copia o array pra ordenar pra não bagunçar a ordem original que o resto da tela usa

    function renderizarDestaques(produtos) {
        const container = document.getElementById('destaques');
        if (!container)
            return;
        container.innerHTML = '';
        if (produtos.length === 0) 
            return;
        const top3 = [...produtos]
            .sort((a, b) => b.vendas - a.vendas)
            .slice(0, 3);
        top3.forEach((produto) => {
            const col = document.createElement('div');
            col.className = 'col-6 col-md-4';
        const card = document.createElement('div');
        card.className = 'card shadow-sm border-0 p-3';
        const posicaoEl = document.createElement('div');
        posicaoEl.className = 'fw-bold text-danger small';
        posicaoEl.textContent = `#${posicao + 1} mais vendido`;
        const nomeEl = document.createElement('div');
        nomeEl.className = 'fw-semibold';
        nomeEl.textContent = produto.nome; // textContent, nunca innerHTML, com dado vindo do banco
        const vendasEl = document.createElement('div');
        vendasEl.className = 'text-muted small';
        vendasEl.textContent = `${produto.vendas} unidades vendidas`;
        card.append(posicaoEl, nomeEl, vendasEl);
        col.appendChild(card);
        container.appendChild(col);
    });
}

    produtos.forEach((produto) => {
        const linha = document.createElement('tr');
        const faturamentoProduto = Number(produto.preco) * produto.vendas;
        linha.innerHTML = `
            <td>${produto.nome}</td>
            <td><span class="badge bg-secondary">${produto.categoria_nome}</span></td>
            <td>${formatarMoeda(Number(produto.preco))}</td>
            <td>${produto.vendas}</td>
            <td>${formatarMoeda(faturamentoProduto)}</td>
        `;
        corpoTabela.appendChild(linha);
    });
}
// --- funçõezinhas de apoio, pra não repetir código em vários lugares ---
function definirTexto(id, texto) {
    const elemento = document.getElementById(id);
    if (elemento) {
        elemento.textContent = texto;
    }
}
function formatarMoeda(valor) {
    // toLocaleString já sabe formatar em R$ sozinho, não precisei
    // escrever essa lógica na mão
    return valor.toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    });
}
function mostrarErroNaTela() {
    const aviso = document.getElementById('dashboard-erro');
    if (aviso) {
        aviso.classList.remove('d-none'); // tira o "escondido" do bootstrap
    }
}
// só começa a rodar depois que o HTML terminar de carregar, senão os
// getElementById aí em cima voltam tudo null (elemento ainda nem
// existe na página)
document.addEventListener('DOMContentLoaded', () => {
    carregarDashboard();
});
//# sourceMappingURL=dashboard.js.map