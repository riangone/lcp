using Microsoft.AspNetCore.Mvc;
using Platform.Infrastructure.Definitions;
using Platform.Infrastructure;
using Platform.Infrastructure.Repositories;
using System;
using System.Linq;

namespace Platform.Api.Controllers;

[ApiController]
[Route("api/{model}")]
public class GenericApiController : ControllerBase
{
    private readonly DynamicRepository _repo;
    private readonly AppDefinitions _defs;

    public GenericApiController(DynamicRepository repo, AppDefinitions defs)
    {
        _repo = repo;
        _defs = defs;
    }

    [HttpGet]
    public async Task<IActionResult> Get(string model)
    {
        var def = GetModel(model);
        var rows = await _repo.GetAllAsync(def);
        return Ok(rows);
    }

    [HttpPost]
    // 注意：CSRF 验证已禁用，通过认证和授权控制访问安全
    // [ValidateAntiForgeryToken]
    public async Task<IActionResult> Create(
        string model,
        [FromForm] Dictionary<string, string> data)
    {
        try
        {
            var def = GetModel(model);
            EnsureWritable(def);
            var objData = ModelBinder.Bind(def, data);

            await _repo.InsertAsync(def, objData);

            Response.Headers["HX-Redirect"] = $"/ui/{model}";
            return Ok();
        }
        catch (Exception ex)
        {
            var errorResponse = new { error = ex.Message };
            return BadRequest(errorResponse);
        }
    }

    [HttpPut("{id}")]
    // 注意：CSRF 验证已禁用，通过认证和授权控制访问安全
    // [ValidateAntiForgeryToken]
    public async Task<IActionResult> Update(
        string model,
        string id,
        [FromForm] Dictionary<string, string> data)
    {
        try
        {
            var def = GetModel(model);
            EnsureWritable(def);
            var objData = ModelBinder.Bind(def, data);

            await _repo.UpdateAsync(def, id, objData);

            Response.Headers["HX-Redirect"] = $"/ui/{model}";
            return Ok();
        }
        catch (Exception ex)
        {
            var errorResponse = new { error = ex.Message };
            return BadRequest(errorResponse);
        }
    }

    [HttpDelete("{id}")]
    // 注意：CSRF 验证已禁用，通过认证和授权控制访问安全
    // [ValidateAntiForgeryToken]
    public async Task<IActionResult> Delete(string model, string id)
    {
        try
        {
            var def = GetModel(model);
            EnsureWritable(def);
            await _repo.DeleteAsync(def, id);

            return Content(string.Empty);
        }
        catch (Exception ex)
        {
            var errorResponse = new { error = ex.Message };
            return BadRequest(errorResponse);
        }
    }

    private ModelDefinition GetModel(string model)
    {
        if (!_defs.AllowedModels.Contains(model))
            throw new Exception($"Model '{model}' not defined");

        // Find the actual key (case-insensitive)
        var actualKey = _defs.Models.Keys.First(k => 
            k.Equals(model, StringComparison.OrdinalIgnoreCase));
        
        return _defs.Models[actualKey];
    }

    private static void EnsureWritable(ModelDefinition def)
    {
        if (def.IsReadOnly)
            throw new Exception("This model is read-only and does not support write operations.");
    }
}
