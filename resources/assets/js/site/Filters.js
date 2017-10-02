export default {
    number(value, decimals = 2) {
        const abs = Math.abs(value);

        if (abs >= Math.pow(10, 13)) {
            return `${(value / Math.pow(10, 12)).toFixed(decimals)}t`;
        } else if (abs >= Math.pow(10, 10)) {
            return `${(value / Math.pow(10, 9)).toFixed(decimals)}b`;
        } else if (abs >= Math.pow(10, 7)) {
            return `${(value / Math.pow(10, 6)).toFixed(decimals)}m`;
        } else if (abs >= Math.pow(10, 4)) {
            return `${(value / Math.pow(10, 3)).toFixed(decimals)}k`;
        }

        return Math.round(value);
    },

    percent(value) {
        return `${value * 100}%`;
    },

    sign(value, number) {
        if (!number) {
            return value;
        }

        if (number < 0) {
            return `-${value}`;
        }

        return `+${value}`;
    },

    timer(value) {
        const abs = Math.abs(value);

        const hours = _.padStart(Math.floor(abs / 3600), 2, '0');
        const minutes = _.padStart(Math.floor(abs / 60 % 60), 2, '0');
        const seconds = _.padStart(Math.floor(abs % 60), 2, '0');

        return `${hours}:${minutes}:${seconds}`;
    }
};
