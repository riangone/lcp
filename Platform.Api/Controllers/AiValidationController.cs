using Microsoft.AspNetCore.Mvc;
using Platform.Application.Services;
using Platform.Infrastructure.Shell;
using System.Threading.Tasks;

namespace Platform.Api.Controllers
{
    [Route("[controller]/[action]")]
    public class AiValidationController : Controller
    {
        private readonly IAiSuggestionService _aiSuggestionService;
        private readonly ISnapshotRepository _snapshotRepository;
        private readonly AiIntegrationService _aiIntegrationService;

        public AiValidationController(
            IAiSuggestionService aiSuggestionService, 
            ISnapshotRepository snapshotRepository,
            AiIntegrationService aiIntegrationService)
        {
            _aiSuggestionService = aiSuggestionService;
            _snapshotRepository = snapshotRepository;
            _aiIntegrationService = aiIntegrationService;
        }

        // GET: /ai-validation
        public IActionResult Index()
        {
            return View();
        }

        // GET: /ai-validation/generate-suggestions/{model}/{id}
        public async Task<IActionResult> GenerateSuggestions(string model, int id)
        {
            // 这里将获取指定模型和ID的数据，然后生成AI建议
            // 为了演示目的，我们暂时返回一个视图
            ViewBag.Model = model;
            ViewBag.Id = id;
            return View();
        }

        // GET: /ai-validation/snapshots/{model}
        public async Task<IActionResult> Snapshots(string model)
        {
            var pendingSnapshots = await _snapshotRepository.GetPendingSnapshotsAsync(model);
            ViewBag.Model = model;
            return View(pendingSnapshots);
        }

        // POST: /ai-validation/approve-snapshot/{id}
        [HttpPost]
        public async Task<IActionResult> ApproveSnapshot(string id)
        {
            await _snapshotRepository.UpdateSnapshotStatusAsync(id, SnapshotStatus.Approved, User?.Identity?.Name ?? "System");
            return RedirectToAction("Snapshots", new { model = Request.Form["model"] });
        }

        // POST: /ai-validation/reject-snapshot/{id}
        [HttpPost]
        public async Task<IActionResult> RejectSnapshot(string id)
        {
            await _snapshotRepository.UpdateSnapshotStatusAsync(id, SnapshotStatus.Rejected, User?.Identity?.Name ?? "System");
            return RedirectToAction("Snapshots", new { model = Request.Form["model"] });
        }

        // GET: /ai-validation/validate
        public IActionResult Validate()
        {
            return View();
        }

        // GET: /ai-validation/test-scenarios
        public IActionResult TestScenarios()
        {
            return View();
        }

        // GET: /ai-validation/definition-layer
        public IActionResult DefinitionLayer()
        {
            return View();
        }

        // GET: /ai-validation/summary
        public IActionResult Summary()
        {
            return View();
        }
    }
}