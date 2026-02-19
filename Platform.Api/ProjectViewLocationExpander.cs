using Microsoft.AspNetCore.Mvc.Razor;
using Microsoft.Extensions.Options;

namespace Platform.Api;

public class ProjectViewLocationExpander : IViewLocationExpander
{
    public void PopulateValues(ViewLocationExpanderContext context)
    {
        context.Values["customlocation"] = "true";
    }

    public IEnumerable<string> ExpandViewLocations(ViewLocationExpanderContext context, IEnumerable<string> viewLocations)
    {
        var extendedLocations = new List<string>();

        // 获取当前项目目录
        var project = context.Values.ContainsKey("project") ? context.Values["project"] : "journal";
        var projectDir = Path.Combine("/home/ubuntu/ws/lcp/Projects", project);

        if (Directory.Exists(projectDir))
        {
            // 添加项目视图目录
            extendedLocations.Add(Path.Combine(projectDir, "views/{1}/{0}.cshtml"));
            extendedLocations.Add(Path.Combine(projectDir, "views/{0}.cshtml"));
        }

        extendedLocations.AddRange(viewLocations);
        return extendedLocations;
    }
}
