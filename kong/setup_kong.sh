#!/bin/sh

# Espera o Kong estar pronto
echo "Aguardando o Kong..."
until curl -s http://kong:8001/status > /dev/null 2>&1; do
    printf '.'
    sleep 2
done
echo ""
echo "Kong está pronto!"

# Verifica se a configuração já foi aplicada
if curl -s http://kong:8001/services | grep -q "catalog-service"; then
    echo "Configuração do Kong já existe. Pulando..."
    exit 0
fi

# Aplica a configuração declarativa
echo "Aplicando configuração declarativa do Kong..."
curl -s -X POST http://kong:8001/config \
    -F config=@/etc/kong/kong.yml

# Verifica se a configuração foi aplicada com sucesso
if [ $? -eq 0 ]; then
    echo "✓ Configuração do Kong aplicada com sucesso!"
    
    # Lista os serviços configurados
    echo ""
    echo "Serviços configurados:"
    curl -s http://kong:8001/services | grep -o '"name":"[^"]*"' | cut -d'"' -f4
    
    echo ""
    echo "Rotas configuradas:"
    curl -s http://kong:8001/routes | grep -o '"paths":\[[^]]*\]' | head -4
    
    echo ""
    echo "Consumidores configurados:"
    curl -s http://kong:8001/consumers | grep -o '"username":"[^"]*"' | cut -d'"' -f4
else
    echo "✗ Erro ao aplicar configuração do Kong"
    exit 1
fi

echo ""
echo "=========================================="
echo "Kong API Gateway configurado com sucesso!"
echo "=========================================="
echo "Proxy HTTP: http://localhost:8000"
echo "Admin API: http://localhost:8001"
echo ""
echo "Rotas disponíveis:"
echo "  - http://localhost:8000/catalog"
echo "  - http://localhost:8000/orders"
echo "  - http://localhost:8000/promotions"
echo "  - http://localhost:8000/payment"
echo "=========================================="
