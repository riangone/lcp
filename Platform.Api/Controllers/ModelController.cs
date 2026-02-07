using Microsoft.AspNetCore.Mvc;
using Platform.Application.Services;

namespace Platform.Api.Controllers;

[ApiController]
[Route("api/models/{model}")]
public class ModelController : ControllerBase
{
private readonly ModelService _service;

    public ModelController(ModelService service)
    {
        _service = service;
    }

    [HttpGet]
    public async Task<IActionResult> Get(string model)
    {
        var data = await _service.GetAll(model);
        return Ok(data);
    }
}
