using Microsoft.AspNetCore.Mvc;
using Platform.Application.Services;

namespace Platform.Api.Controllers;

[ApiController]
[Route("api/auth")]
public class AuthController : ControllerBase
{
private readonly AuthService _auth;

    public AuthController(AuthService auth)
    {
        _auth = auth;
    }

    [HttpPost("login")]
    public async Task<IActionResult> Login([FromBody] LoginDto dto)
    {
        var user = await _auth.Login(dto.Email, dto.Password);

        if (user == null)
            return Unauthorized();

        return Ok(user);
    }
}

public class LoginDto
{
public string Email { get; set; } = "";
public string Password { get; set; } = "";
}
