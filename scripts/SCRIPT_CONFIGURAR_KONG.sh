#!/bin/bash

# Script para configurar automaticamente as rotas no Kong
# Execute este script na pasta do projeto para configurar todos os serviços

echo "=========================================="
echo "Configurando rotas no Kong"
echo "=========================================="
echo ""

# Aguardar o Kong ficar pronto
echo "Aguardando Kong ficar pronto..."
sleep 5

# Função para configurar um serviço
configurar_servico() {
    local service_name=$1
    local service_url=$2
    local route_path=$3

    echo "Configurando $service_name..."
    
    # Criar o serviço
    curl -X POST http://localhost:8001/services \
      -H "Content-Type: application/json" \
      -d "{
        \"name\": \"$service_name-service\",
        \"url\": \"$service_url\"
      }" \
      -s -o /dev/null

    # Criar a rota
    curl -X POST http://localhost:8001/services/$service_name-service/routes \
      -H "Content-Type: application/json" \
      -d "{
        \"paths\": [\"$route_path\"]
      }" \
      -s -o /dev/null

    echo "✓ $service_name configurado"
}

# Configurar todos os serviços
configurar_servico "catalog" "http://catalog:80" "/catalog"
configurar_servico "orders" "http://orders:80" "/orders"
configurar_servico "promotions" "http://promotions:80" "/promotions"
configurar_servico "payment" "http://payment:80" "/payment"

echo ""
echo "=========================================="
echo "✓ Configuração concluída!"
echo "=========================================="
echo ""
echo "Rotas disponíveis:"
echo "  - http://localhost:8000/catalog/products"
echo "  - http://localhost:8000/orders/orders"
echo "  - http://localhost:8000/promotions/promotions"
echo "  - http://localhost:8000/payment/payment"
echo ""
