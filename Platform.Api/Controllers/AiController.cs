using Microsoft.AspNetCore.Mvc;
using Platform.Application.Services;
using Platform.Infrastructure.Definitions;
using Platform.Infrastructure.Repositories;
using Platform.Infrastructure.Shell;
using System;
using System.Linq;
using System.Threading.Tasks;

namespace Platform.Api.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class AiController : ControllerBase
    {
        private readonly AiIntegrationService _aiIntegrationService;
        private readonly ISnapshotRepository _snapshotRepository;
        private readonly DynamicRepository _dynamicRepository;
        private readonly AppDefinitions _definitions;

        public AiController(
            AiIntegrationService aiIntegrationService,
            ISnapshotRepository snapshotRepository,
            DynamicRepository dynamicRepository,
            AppDefinitions definitions)
        {
            _aiIntegrationService = aiIntegrationService;
            _snapshotRepository = snapshotRepository;
            _dynamicRepository = dynamicRepository;
            _definitions = definitions;
        }

        /// <summary>
        /// 为指定模型和ID的数据生成AI建议
        /// </summary>
        [HttpPost("suggest/{model}/{id}")]
        public async Task<IActionResult> GenerateSuggestions(string model, string id)
        {
            try
            {
                // 验证模型是否存在
                var modelDef = GetModelDefinition(model);
                if (modelDef == null)
                {
                    return NotFound($"Model '{model}' not found");
                }

                // 获取当前数据
                var currentData = await _dynamicRepository.GetByIdAsync(modelDef, id);
                if (currentData == null)
                {
                    return NotFound($"Item with ID '{id}' not found in model '{model}'");
                }

                // 生成AI建议并创建快照
                var snapshotIds = await _aiIntegrationService.GenerateAndStabilizeSuggestionsAsync(
                    currentData, model);

                return Ok(new
                {
                    Message = "AI suggestions generated and stabilized successfully",
                    SnapshotIds = snapshotIds,
                    Count = snapshotIds.Count
                });
            }
            catch (System.Exception ex)
            {
                return BadRequest(new { Error = ex.Message });
            }
        }

        /// <summary>
        /// 获取指定模型的待审批快照
        /// </summary>
        [HttpGet("pending/{model}")]
        public async Task<IActionResult> GetPendingSnapshots(string model)
        {
            try
            {
                // 验证模型是否存在
                var modelDef = GetModelDefinition(model);
                if (modelDef == null)
                {
                    return NotFound($"Model '{model}' not found");
                }

                var snapshots = await _snapshotRepository.GetPendingSnapshotsAsync(model);
                return Ok(snapshots);
            }
            catch (System.Exception ex)
            {
                return BadRequest(new { Error = ex.Message });
            }
        }

        /// <summary>
        /// 审批快照
        /// </summary>
        [HttpPost("approve/{snapshotId}")]
        public async Task<IActionResult> ApproveSnapshot(string snapshotId, [FromBody] ApproveRequest request)
        {
            try
            {
                await _snapshotRepository.UpdateSnapshotStatusAsync(
                    snapshotId, SnapshotStatus.Approved, request.ApprovedBy);

                return Ok(new { Message = "Snapshot approved successfully" });
            }
            catch (System.Exception ex)
            {
                return BadRequest(new { Error = ex.Message });
            }
        }

        /// <summary>
        /// 拒绝快照
        /// </summary>
        [HttpPost("reject/{snapshotId}")]
        public async Task<IActionResult> RejectSnapshot(string snapshotId, [FromBody] ApproveRequest request)
        {
            try
            {
                await _snapshotRepository.UpdateSnapshotStatusAsync(
                    snapshotId, SnapshotStatus.Rejected, request.ApprovedBy);

                return Ok(new { Message = "Snapshot rejected successfully" });
            }
            catch (System.Exception ex)
            {
                return BadRequest(new { Error = ex.Message });
            }
        }

        private ModelDefinition GetModelDefinition(string model)
        {
            if (!_definitions.AllowedModels.Contains(model))
                return null;

            // Find the actual key (case-insensitive)
            var actualKey = _definitions.Models.Keys.First(k =>
                k.Equals(model, StringComparison.OrdinalIgnoreCase));

            return _definitions.Models[actualKey];
        }
    }

    public class ApproveRequest
    {
        public string ApprovedBy { get; set; } = string.Empty;
    }
}