# Tebex Headless API PHP Wrapper

![Tebex Headless](https://cdn.discordapp.com/attachments/819864517680562198/1179846599543898122/tebex.png?ex=657b44c5&is=6568cfc5&hm=3088d82d0954d66cc0d4436f612011596bfb1583d229abae14a8d655e040ee8f&)

Welcome to the Tebex Headless API PHP Wrapper! This PHP library allows you to seamlessly integrate Tebex into your projects and build a custom storefront without revealing your use of Tebex.

## Getting Started

1. Obtain your Tebex Headless API public token from your Tebex account.
2. Install the `tebex_headless_php` library in your project.
3. Start using the Tebex Headless API in your PHP application!

```php
require_once("tebex_headless_php/tebex_headless.php");

SetWebstoreIdentifier("your_public_token")
```

## Documentation

The documentation is available [here](https://nabla-corporation.github.io/tebex_headless_php/tebex__headless_8php.html)

## Features

- **Seamless Integration:** Use Tebex functionalities in your project without exposing the fact that you are using Tebex.
- **Custom Storefront:** Build your custom storefront by leveraging Tebex Headless API.

<!--
## IP forwarding

If a store uses its own backend but wants to use the IP addresses of the users instead of the server, Tebex requires a basic [authentication](https://documenter.getpostman.com/view/10912536/2s9XxvTEmh#intro).

[Check our following Link to generate a private key](https://creator.tebex.io/developers/api-keys)

```typescript
import { SetWebstoreIdentifier, SetPrivateKey } from "tebex_headless";

SetWebstoreIdentifier("your_public_token")
SetPrivateKey("your_private_key")
```
-->

## Contributing

We welcome contributions from the community! If you'd like to contribute to this project, please follow these steps:

1. Fork the repository.
2. Create a new branch for your feature or bug fix.
3. Make your changes and ensure tests pass.
4. Submit a pull request with a clear description of your changes.

## Documentation

Check out the [official Tebex documentation](https://docs.tebex.io/) for detailed information about the Tebex Headless API and its features.

## Support

If you have questions or need assistance, feel free to [open an issue](https://github.com/Nabla-Corporation/tebex_headless_php/issues) on the GitHub repository.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

Happy coding! 🚀