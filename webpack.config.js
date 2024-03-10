const path = require('path')
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");

module.exports = {
    mode: 'development',
    entry: {
        public: './assets/src/public.js',
        admin: './assets/src/admin.js',
    },
    module: {
        rules: [
            {
                test: /\.css$/i,
                include: path.resolve(__dirname, 'assets/src'),
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader,
                        options: {
                            publicPath: "./assets/src/",
                        },
                    },
                    'css-loader',
                    'postcss-loader'],
            },
            {
                test: /\.(png|jpg|gif)$/i,
                type: 'asset/resource',
            },
        ],
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: '../assets/css/[name].css',
        })
    ],
    optimization: {
        minimizer: [
            `...`,
            new CssMinimizerPlugin(),

        ],
    },
}
