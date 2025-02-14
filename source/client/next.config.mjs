/** @type {import('next').NextConfig} */
const nextConfig = {
    images: {
        remotePatterns: [
            {
                protocol: 'https',
                hostname: 'placehold.co',
                port: '',
                pathname: '/**', 
            }
        ]
    },
    webpack: config => {
        config.watchOptions = {
          poll: 1000,
          aggregateTimeout: 300,
        }
        return config
    }
};

export default nextConfig;
