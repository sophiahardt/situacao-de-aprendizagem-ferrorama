(() => {
    const localizacao = document.querySelector("#clima-local");
    const temperatura = document.querySelector("#clima-temperatura");
    const condicao = document.querySelector("#clima-condicao");
    const icone = document.querySelector("#clima-icone");
    const umidade = document.querySelector("#clima-umidade");
    const vento = document.querySelector("#clima-vento");
    const atualizacao = document.querySelector("#clima-atualizacao");
    const tentarNovamente = document.querySelector("#clima-tentar-novamente");

    if (!(localizacao instanceof HTMLElement)
        || !(temperatura instanceof HTMLElement)
        || !(condicao instanceof HTMLElement)
        || !(icone instanceof HTMLElement)
        || !(umidade instanceof HTMLElement)
        || !(vento instanceof HTMLElement)
        || !(atualizacao instanceof HTMLElement)
        || !(tentarNovamente instanceof HTMLButtonElement)) {
        return;
    }

    const LOCAL_PADRAO = {
        nome: "Joinville, SC",
        latitude: -26.3044,
        longitude: -48.8461
    };

    const condicoes = {
        0: ["Céu limpo", "sunny"],
        1: ["Predominantemente limpo", "partly-sunny"],
        2: ["Parcialmente nublado", "partly-sunny"],
        3: ["Nublado", "cloudy"],
        45: ["Nevoeiro", "cloudy"],
        48: ["Nevoeiro com geada", "cloudy"],
        51: ["Chuvisco leve", "rainy"],
        53: ["Chuvisco moderado", "rainy"],
        55: ["Chuvisco intenso", "rainy"],
        56: ["Chuvisco congelante leve", "rainy"],
        57: ["Chuvisco congelante intenso", "rainy"],
        61: ["Chuva leve", "rainy"],
        63: ["Chuva moderada", "rainy"],
        65: ["Chuva forte", "rainy"],
        66: ["Chuva congelante leve", "rainy"],
        67: ["Chuva congelante intensa", "rainy"],
        71: ["Neve leve", "snow"],
        73: ["Neve moderada", "snow"],
        75: ["Neve forte", "snow"],
        77: ["Grãos de neve", "snow"],
        80: ["Pancadas de chuva leves", "rainy"],
        81: ["Pancadas de chuva moderadas", "rainy"],
        82: ["Pancadas de chuva fortes", "rainy"],
        85: ["Pancadas de neve leves", "snow"],
        86: ["Pancadas de neve fortes", "snow"],
        95: ["Trovoada", "thunderstorm"],
        96: ["Trovoada com granizo leve", "thunderstorm"],
        99: ["Trovoada com granizo forte", "thunderstorm"]
    };

    const mostrarErro = (texto) => {
        condicao.textContent = "Clima indisponível";
        atualizacao.textContent = texto;
        tentarNovamente.classList.remove("d-none");
    };

    const obterPosicao = (solicitarLocalizacao = false) => new Promise((resolve, reject) => {
        if (!navigator.geolocation) {
            reject(new Error("Este navegador não oferece acesso à localização."));
            return;
        }

        if (!solicitarLocalizacao) {
            resolve({
                coords: {
                    latitude: LOCAL_PADRAO.latitude,
                    longitude: LOCAL_PADRAO.longitude
                }
            });
            return;
        }

        navigator.geolocation.getCurrentPosition(resolve, (erro) => {
            if (erro.code === 1) {
                resolve({
                    coords: {
                        latitude: LOCAL_PADRAO.latitude,
                        longitude: LOCAL_PADRAO.longitude
                    }
                });
                return;
            }

            if (erro.code === 3) {
                reject(new Error("A localização demorou demais para responder. Tente novamente."));
                return;
            }

            reject(new Error("Não foi possível obter sua localização. Tente novamente."));
        }, { enableHighAccuracy: false, timeout: 10000, maximumAge: 300000 });
    });

    const carregarClima = async (solicitarLocalizacao = false) => {
        tentarNovamente.classList.add("d-none");
        condicao.textContent = "Carregando clima...";
        atualizacao.textContent = solicitarLocalizacao ? "Solicitando sua localização..." : "Usando cidade padrão...";

        try {
            const posicao = await obterPosicao(solicitarLocalizacao);
            const latitude = posicao.coords.latitude;
            const longitude = posicao.coords.longitude;
            const usarLocalPadrao = !solicitarLocalizacao || (Math.abs(latitude - LOCAL_PADRAO.latitude) < 0.0001 && Math.abs(longitude - LOCAL_PADRAO.longitude) < 0.0001);

            const parametros = new URLSearchParams({
                latitude: latitude.toString(),
                longitude: longitude.toString(),
                current: "temperature_2m,relative_humidity_2m,is_day,weather_code,wind_speed_10m",
                timezone: "auto"
            });

            const controlador = new AbortController();
            const timeout = window.setTimeout(() => controlador.abort(), 10000);
            let resposta;

            try {
                resposta = await fetch(`https://api.open-meteo.com/v1/forecast?${parametros}`, {
                    signal: controlador.signal
                });
            } catch (erro) {
                if (erro.name === "AbortError") {
                    throw new Error("A consulta do clima demorou demais. Tente novamente.");
                }
                throw new Error("Não foi possível conectar ao serviço de clima. Verifique sua conexão.");
            } finally {
                window.clearTimeout(timeout);
            }

            if (!resposta.ok) {
                throw new Error("O serviço de clima está indisponível no momento.");
            }

            let dados;
            try {
                dados = await resposta.json();
            } catch {
                throw new Error("O serviço de clima retornou uma resposta inválida.");
            }

            const atual = dados.current;
            if (!atual || !Number.isFinite(atual.temperature_2m)
                || !Number.isFinite(atual.relative_humidity_2m)
                || !Number.isFinite(atual.is_day)
                || !Number.isFinite(atual.wind_speed_10m)
                || !Number.isFinite(atual.weather_code)
                || typeof atual.time !== "string") {
                throw new Error("O serviço de clima retornou dados incompletos.");
            }

            const [descricao, nomeIcone] = condicoes[atual.weather_code] ?? ["Condição não identificada", "cloudy"];
            const [data, horario] = atual.time.split("T");
            const [ano, mes, dia] = data.split("-");

            localizacao.textContent = usarLocalPadrao ? `Local padrão: ${LOCAL_PADRAO.nome}` : `Sua localização (${latitude.toFixed(2)}, ${longitude.toFixed(2)})`;
            temperatura.textContent = `${Math.round(atual.temperature_2m)}°C`;
            condicao.textContent = descricao;
            icone.setAttribute("name", atual.weather_code === 0 && !atual.is_day ? "moon" : nomeIcone);
            umidade.textContent = `${Math.round(atual.relative_humidity_2m)}%`;
            vento.textContent = `${Math.round(atual.wind_speed_10m)} km/h`;
            atualizacao.textContent = usarLocalPadrao
                ? `Local padrão: Joinville, SC. Permissão de localização não foi concedida.`
                : `Atualizado em ${dia}/${mes}/${ano} às ${horario} (${dados.timezone ?? "horário local"})`;
        } catch (erro) {
            mostrarErro(erro.message || "Não foi possível carregar o clima. Tente novamente.");
        }
    };

    tentarNovamente.addEventListener("click", () => carregarClima(true));
    carregarClima(false);
})();
