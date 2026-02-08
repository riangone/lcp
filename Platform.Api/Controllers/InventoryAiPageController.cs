using Microsoft.AspNetCore.Mvc;

namespace Platform.Api.Controllers
{
    public class InventoryAiPageController : Controller
    {
        public IActionResult Index()
        {
            return View("~/Views/InventoryAi/Index.cshtml");
        }
    }
}