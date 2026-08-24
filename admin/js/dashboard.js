"use strict";

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
        renderizarDashboard(produtos);
    }
    catch (erro) {
        // cai aqui se: rede caiu, api não achou o arquivo, banco fora
        // do ar, ou o json veio zoado. mostra aviso na tela em vez de
        // deixar tudo travado em "Carregando..." pra sempre
        console.error('Erro ao carregar o dashboard:', erro);
        mostrarErroNaTela();
    }
}
// soma preço x vendas de cada produto = faturamento total
function calcularFaturamentoTotal(produtos) {
    // se não tem produto nenhum, não tem faturamento — sem esse if o
    // reduce ainda funcionaria (dava 0 igual), mas deixei explícito
    // pra não correr risco nenhum de dar NaN em algum caso estranho
    if (produtos.length === 0) {
        return 0;
    }
    return produtos.reduce((total, produto) => {
        const precoUnitario = Number(produto.preco); // converte de string pra numero
        const faturamentoDesseProduto = precoUnitario * produto.vendas;
        return total + faturamentoDesseProduto;
    }, 0);

}
// escreve tudo na tela — cards e a tabela
function renderizarDashboard(produtos) {
    definirTexto('card-total-produtos', produtos.length.toString());
    const faturamento = calcularFaturamentoTotal(produtos);
    definirTexto('card-faturamento', formatarMoeda(faturamento));
    const corpoTabela = document.getElementById('tabela-produtos-corpo');
    if (!corpoTabela)
        return;
    corpoTabela.innerHTML = '';
    if (produtos.length === 0) {
        // catálogo vazio — mostra aviso em vez de deixar em branco
        corpoTabela.innerHTML = `
            <tr>
                <td colspan="5" class="text-center text-muted py-4">
                    Nenhum dado registrado.
                </td>
            </tr>
        `;
        return;
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